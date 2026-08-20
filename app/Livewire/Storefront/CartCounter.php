<?php

namespace App\Livewire\Storefront;

use App\Services\Cart;
use Livewire\Attributes\On;
use Livewire\Component;

class CartCounter extends Component
{
    #[On('cart-updated')]
    public function refresh(): void
    {
        // no-op: re-rendering picks up the fresh cart count
    }

    public function render(Cart $cart)
    {
        return view('livewire.storefront.cart-counter', [
            'count' => $cart->count(),
        ]);
    }
}
