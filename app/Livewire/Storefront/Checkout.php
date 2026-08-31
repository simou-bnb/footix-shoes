<?php

namespace App\Livewire\Storefront;

use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\Wilaya;
use App\Services\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class Checkout extends Component
{
    public string $customerName = '';

    public string $customerPhone = '';

    public ?int $wilayaId = null;

    public string $commune = '';

    public string $address = '';

    public string $deliveryType = 'home';

    /**
     * Honeypot: left empty by real visitors, often auto-filled by bots.
     * Never shown in the UI on purpose — see the wrapper in the Blade view.
     */
    public string $website = '';

    protected function rules(): array
    {
        return [
            'customerName' => 'required|string|max:255',
            'customerPhone' => 'required|string|max:20',
            'wilayaId' => 'required|exists:wilayas,id',
            'commune' => 'required|string|max:255',
            'address' => $this->deliveryType === 'home' ? 'required|string|max:500' : 'nullable|string|max:500',
            'deliveryType' => 'required|in:home,stopdesk',
        ];
    }

    protected array $messages = [
        'customerName.required' => 'Indique ton nom.',
        'customerPhone.required' => 'Indique ton numéro de téléphone.',
        'wilayaId.required' => 'Choisis ta wilaya.',
        'commune.required' => 'Indique ta commune.',
        'address.required' => 'Indique ton adresse pour la livraison à domicile.',
    ];

    public function getSelectedWilayaProperty(): ?Wilaya
    {
        return $this->wilayaId ? Wilaya::find($this->wilayaId) : null;
    }

    public function getDeliveryPriceProperty(): float
    {
        $wilaya = $this->selectedWilaya;

        if (! $wilaya) {
            return 0;
        }

        return (float) ($this->deliveryType === 'stopdesk' ? $wilaya->stopdesk_delivery_price : $wilaya->home_delivery_price);
    }

    public function updatedWilayaId(): void
    {
        if ($this->deliveryType === 'stopdesk' && ! $this->selectedWilaya?->hasStopDesk()) {
            $this->deliveryType = 'home';
        }
    }

    public function placeOrder(Cart $cart)
    {
        if (filled($this->website)) {
            return;
        }

        $rateLimitKey = 'place-order:'.request()->ip();

        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $this->addError('cart', 'Trop de commandes envoyées d\'affilée. Réessaie dans quelques minutes.');

            return;
        }

        RateLimiter::hit($rateLimitKey, 3600);

        $this->validate();

        $items = $cart->items();

        if ($items->isEmpty()) {
            $this->addError('cart', 'Ton panier est vide.');

            return;
        }

        $wilaya = $this->selectedWilaya;

        if ($this->deliveryType === 'stopdesk' && ! $wilaya->hasStopDesk()) {
            $this->deliveryType = 'home';
            $this->addError('deliveryType', 'Le stop desk n\'est pas disponible pour cette wilaya.');

            return;
        }

        foreach ($items as $item) {
            $fresh = ProductVariant::find($item->variant->id);

            if (! $fresh || ! $fresh->is_active || $fresh->stock < $item->quantity) {
                $this->addError('cart', 'Le stock a changé pour "'.$item->variant->product->name.'". Vérifie ton panier.');

                return;
            }
        }

        $deliveryPrice = $this->deliveryPrice;
        $subtotal = $items->sum('lineTotal');
        $total = $subtotal + $deliveryPrice;

        $order = DB::transaction(function () use ($items, $wilaya, $deliveryPrice, $subtotal, $total) {
            $order = Order::create([
                'customer_name' => $this->customerName,
                'customer_phone' => $this->customerPhone,
                'wilaya_id' => $wilaya->id,
                'commune' => $this->commune,
                'address' => $this->address ?: null,
                'delivery_type' => $this->deliveryType,
                'delivery_price' => $deliveryPrice,
                'subtotal' => $subtotal,
                'total' => $total,
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_variant_id' => $item->variant->id,
                    'product_name' => $item->variant->product->name,
                    'variant_label' => $item->variant->label,
                    'sku' => $item->variant->sku,
                    'unit_price' => $item->unitPrice,
                    'quantity' => $item->quantity,
                    'line_total' => $item->lineTotal,
                ]);

                $item->variant->decrement('stock', $item->quantity);
            }

            return $order;
        });

        $cart->clear();
        $this->dispatch('cart-updated');

        \App\Jobs\SendMetaPurchaseEvent::dispatch(
            $order,
            request()->ip(),
            request()->userAgent()
        );

        $this->redirect(route('order.confirmation', $order), navigate: true);
    }

    public function render(Cart $cart)
    {
        return view('livewire.storefront.checkout', [
            'items' => $cart->items(),
            'subtotal' => $cart->subtotal(),
            'wilayas' => Wilaya::query()->where('is_active', true)->orderBy('name')->get(),
        ])->layout('layouts.storefront', ['title' => 'Commander — Footix Shoes']);
    }
}
