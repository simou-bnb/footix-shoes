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
    }

    public function render()
    {
        return view('livewire.storefront.order-confirmation')
            ->layout('layouts.storefront', ['title' => 'Commande confirmée — Footix Shoes']);
    }
}
