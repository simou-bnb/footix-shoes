<div>
    <section class="border-b border-black">
        <img src="{{ asset('images/hero-banner.jpg') }}" alt="Footix Shoes — Classic &amp; Classic Sport" class="w-full h-auto">
    </section>

    @if ($categories->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @foreach ($categories as $category)
                    <a href="{{ route('category.show', $category) }}" wire:navigate class="group relative aspect-[4/3] bg-neutral-100 overflow-hidden flex items-end">
                        @if ($category->image)
                            <img src="{{ Storage::disk('public')->url($category->image) }}" alt="{{ $category->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-colors"></div>
                        @endif
                        <span class="relative z-10 p-4 text-white font-bold uppercase tracking-wide {{ $category->image ? '' : 'text-black' }}">
                            {{ $category->name }}
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @if ($products->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
            <h2 class="text-xl font-bold uppercase tracking-wide mb-6">{{ __('Nouveautés') }}</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4 gap-y-8">
                @foreach ($products as $product)
                    @include('livewire.storefront.partials.product-card', ['product' => $product])
                @endforeach
            </div>
        </section>
    @endif
</div>
