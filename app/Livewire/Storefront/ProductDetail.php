<?php

namespace App\Livewire\Storefront;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Cart;
use Illuminate\Support\Collection;
use Livewire\Component;

class ProductDetail extends Component
{
    public Product $product;

    public ?string $selectedSize = null;

    public ?string $selectedColor = null;

    public int $quantity = 1;

    public ?string $errorMessage = null;

    public function mount(Product $product): void
    {
        $this->product = $product->load(['images' => fn ($q) => $q->orderBy('sort_order'), 'variants' => fn ($q) => $q->where('is_active', true)]);

        if ($this->product->variants->count() === 1) {
            $only = $this->product->variants->first();
            $this->selectedSize = $only->size;
            $this->selectedColor = $only->color;
        }

        $this->dispatch('meta-pixel', [
            'event' => 'ViewContent',
            'data'  => [
                'content_ids'  => [(string) $this->product->id],
                'content_name' => $this->product->name,
                'content_type' => 'product',
                'value'        => (float) $this->product->base_price,
                'currency'     => 'DZD',
            ],
        ]);
    }

    public function updatedSelectedSize(): void
    {
        $this->selectedColor = null;
        $this->errorMessage = null;
    }

    public function updatedSelectedColor(): void
    {
        $this->errorMessage = null;
    }

    public function getSizesProperty(): Collection
    {
        return $this->product->variants->pluck('size')->filter()->unique()->values();
    }

    public function getColorsProperty(): Collection
    {
        return $this->product->variants
            ->when($this->selectedSize, fn ($v) => $v->where('size', $this->selectedSize))
            ->pluck('color')->filter()->unique()->values();
    }

    public function getSelectedVariantProperty(): ?ProductVariant
    {
        return $this->product->variants->first(
            fn (ProductVariant $v) => $v->size === $this->selectedSize && $v->color === $this->selectedColor
        );
    }

    public function addToCart(Cart $cart): void
    {
        if (! $this->putSelectionInCart($cart)) {
            return;
        }

        session()->flash('added', $this->product->name);
    }

    public function orderNow(Cart $cart)
    {
        if (! $this->putSelectionInCart($cart)) {
            return;
        }

        return $this->redirect(route('checkout'), navigate: true);
    }

    protected function putSelectionInCart(Cart $cart): bool
    {
        $variant = $this->selectedVariant;

        if (! $variant) {
            $this->errorMessage = 'Choisis une taille/couleur avant de continuer.';

            return false;
        }

        if ($variant->stock < $this->quantity) {
            $this->errorMessage = 'Stock insuffisant pour cette variante.';

            return false;
        }

        $cart->add($variant->id, $this->quantity);
        $this->dispatch('cart-updated');
        $this->errorMessage = null;

        return true;
    }

    public function render()
    {
        $description = $this->product->description
            ? str(strip_tags($this->product->description))->limit(155)->toString()
            : $this->product->name.' — disponible sur Footix Shoes, livraison partout en Algérie.';

        return view('livewire.storefront.product-detail')->layout('layouts.storefront', [
            'title' => $this->product->name.' — Footix Shoes',
            'description' => $description,
            'ogImage' => $this->product->images->first() ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->product->images->first()->path) : null,
            'ogUrl' => request()->url(),
        ]);
    }
}
