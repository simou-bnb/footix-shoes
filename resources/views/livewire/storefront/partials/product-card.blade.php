@php
    $image = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
@endphp

<a href="{{ route('product.show', $product) }}" wire:navigate class="group block">
    <div class="aspect-square bg-neutral-100 overflow-hidden mb-3">
        @if ($image)
            <img src="{{ Storage::disk('public')->url($image->path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
        @else
            <div class="w-full h-full flex items-center justify-center text-neutral-300 text-xs uppercase tracking-wide">
                {{ __('Pas de photo') }}
            </div>
        @endif
    </div>
    <h3 class="text-sm font-medium uppercase tracking-wide">{{ $product->name }}</h3>
    <p class="text-sm text-black/60 mt-1" dir="ltr">{{ number_format($product->base_price, 0, ',', ' ') }} DA</p>
</a>
