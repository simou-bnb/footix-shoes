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

        $total     = (float) $this->order->total;
        $ids       = $this->order->items->pluck('product_variant_id')->map(fn ($id) => "'$id'")->implode(',');
        $numItems  = (int) $this->order->items->sum('quantity');

        $this->js("
            if (typeof fbq !== 'undefined') {
                fbq('track', 'Purchase', {
                    value: {$total},
                    currency: 'DZD',
                    content_type: 'product',
                    content_ids: [{$ids}],
                    num_items: {$numItems}
                });
            }
        ");
    }

    public function render()
    {
        return view('livewire.storefront.order-confirmation')
            ->layout('layouts.storefront', ['title' => 'Commande confirmée — Footix Shoes']);
    }
}
