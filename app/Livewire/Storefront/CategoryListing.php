<?php

namespace App\Livewire\Storefront;

use App\Models\Category;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryListing extends Component
{
    use WithPagination;

    public Category $category;

    #[Url]
    public string $sort = 'newest';

    public function mount(Category $category): void
    {
        $this->category = $category;
    }

    public function render()
    {
        $query = $this->category->products()
            ->where('is_active', true)
            ->with(['images' => fn ($q) => $q->orderBy('sort_order')]);

        match ($this->sort) {
            'price_asc' => $query->orderBy('base_price'),
            'price_desc' => $query->orderByDesc('base_price'),
            default => $query->latest(),
        };

        return view('livewire.storefront.category-listing', [
            'products' => $query->paginate(12),
        ])->layout('layouts.storefront', [
            'title' => $this->category->name.' — Footix Shoes',
            'description' => 'Découvre notre sélection '.$this->category->name.' — livraison partout en Algérie, paiement à la réception.',
        ]);
    }
}
