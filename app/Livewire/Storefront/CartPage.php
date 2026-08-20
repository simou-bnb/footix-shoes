<?php

namespace App\Livewire\Storefront;

use App\Services\Cart;
use Livewire\Component;

class CartPage extends Component
{
    public function updateQuantity(Cart $cart, int $variantId, int $quantity): void
    {
        $cart->update($variantId, $quantity);
        $this->dispatch('cart-updated');
    }

    public function removeItem(Cart $cart, int $variantId): void
    {
        $cart->remove($variantId);
        $this->dispatch('cart-updated');
    }

    public function render(Cart $cart)
    {
        return view('livewire.storefront.cart-page', [
            'items' => $cart->items(),
            'subtotal' => $cart->subtotal(),
        ])->layout('layouts.storefront', ['title' => 'Panier — Footix Shoes']);
    }
}
