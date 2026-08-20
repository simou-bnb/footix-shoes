<?php

namespace App\Livewire\Storefront;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.storefront.home', [
            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
            'products' => Product::query()
                ->where('is_active', true)
                ->with(['images' => fn ($q) => $q->orderBy('sort_order')])
                ->latest()
                ->limit(8)
                ->get(),
        ])->layout('layouts.storefront', [
            'title' => 'Footix Shoes — Chaussures, vêtements & accessoires en Algérie',
            'description' => 'Footix Shoes : chaussures, vêtements et accessoires en Algérie. Livraison dans toutes les wilayas, paiement à la réception.',
        ]);
    }
}
