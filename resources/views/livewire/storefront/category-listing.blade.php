<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
        <div class="flex items-end justify-between flex-wrap gap-4 mb-8 border-b border-black pb-6">
            <h1 class="text-3xl font-extrabold uppercase tracking-tight">{{ $category->name }}</h1>

            <select wire:model.live="sort" class="border border-black px-3 py-2 text-sm uppercase tracking-wide bg-white">
                <option value="newest">{{ __('Nouveautés') }}</option>
                <option value="price_asc">{{ __('Prix croissant') }}</option>
                <option value="price_desc">{{ __('Prix décroissant') }}</option>
            </select>
        </div>

        @if ($products->isEmpty())
            <p class="text-black/60 py-12 text-center">{{ __('Aucun produit dans cette catégorie pour le moment.') }}</p>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-8">
                @foreach ($products as $product)
                    @include('livewire.storefront.partials.product-card', ['product' => $product])
                @endforeach
            </div>

            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
