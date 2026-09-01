<?php

namespace App\Livewire\Storefront;

use App\Models\Order;
use Livewire\Component;

class OrderConfirmation extends Component
{
    public Order $order;

    public function mount(Order $order): void
    {
        $this->order = $order->load('items', 'wilaya');

        $this->dispatch('meta-pixel', [
            'event' => 'Purchase',
            'data'  => [
                'value'        => (float) $this->order->total,
                'currency'     => 'DZD',
                'content_type' => 'product',
                'content_ids'  => $this->order->items->pluck('product_variant_id')->map(fn ($id) => (string) $id)->toArray(),
                'num_items'    => $this->order->items->sum('quantity'),
            ],
        ]);
    }

    public function render()
    {
        return view('livewire.storefront.order-confirmation')
            ->layout('layouts.storefront', ['title' => 'Commande confirmée — Footix Shoes']);
    }
}
