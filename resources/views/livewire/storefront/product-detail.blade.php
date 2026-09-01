@php
    $displayPrice = $this->selectedVariant?->effective_price ?? $product->base_price;
    $mainImage = $product->images->first();
@endphp

@php $pixelData = base64_encode(json_encode(['content_ids'=>[(string)$product->id],'content_name'=>$product->name,'content_type'=>'product','value'=>(float)$displayPrice,'currency'=>'DZD'])); @endphp
<div x-data="{ selectedImage: '{{ $mainImage ? Storage::disk('public')->url($mainImage->path) : '' }}' }"
    data-pixel-event="ViewContent"
    data-pixel-data="{{ $pixelData }}"
    @cart-updated.window="if(typeof fbq !== 'undefined') fbq('track', 'AddToCart', {
        content_ids: ['{{ $product->id }}'],
        content_name: '{{ addslashes($product->name) }}',
        content_type: 'product',
        value: {{ $displayPrice }},
        currency: 'DZD'
    })"
    class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    @if (session('added'))
        <div class="mb-6 border border-black bg-black text-white px-4 py-3 text-sm flex items-center justify-between">
            <span>{{ session('added') }} {{ __('ajouté au panier.') }}</span>
            <a href="{{ route('cart') }}" wire:navigate class="underline font-medium">{{ __('Voir le panier') }}</a>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        {{-- Gallery --}}
        <div>
            <div class="aspect-square bg-white overflow-hidden mb-3 border border-neutral-100">
                @if ($mainImage)
                    <img :src="selectedImage" alt="{{ $product->name }}" class="w-full h-full object-contain">
                @else
                    <div class="w-full h-full flex items-center justify-center text-neutral-300 text-xs uppercase tracking-wide">{{ __('Pas de photo') }}</div>
                @endif
            </div>
            @if ($product->images->count() > 1)
                <div class="grid grid-cols-5 gap-2">
                    @foreach ($product->images as $image)
                        <div class="aspect-square bg-white overflow-hidden cursor-pointer border hover:border-black transition-colors"
                             :class="{ 'border-black': selectedImage === '{{ Storage::disk('public')->url($image->path) }}', 'border-neutral-100': selectedImage !== '{{ Storage::disk('public')->url($image->path) }}' }"
                             @click="selectedImage = '{{ Storage::disk('public')->url($image->path) }}'">
                            <img src="{{ Storage::disk('public')->url($image->path) }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-1">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div>
            <p class="text-xs uppercase tracking-widest text-black/50 mb-2">{{ $product->category->name }}</p>
            <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight mb-3">{{ $product->name }}</h1>
            <p class="text-xl font-medium mb-6" dir="ltr" style="text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};">{{ number_format($displayPrice, 0, ',', ' ') }} DA</p>

            @if ($this->sizes->isNotEmpty())
                <div class="mb-5">
                    <p class="text-xs uppercase tracking-wide font-medium mb-2">{{ __('Taille') }}</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($this->sizes as $size)
                            <button type="button" wire:click="$set('selectedSize', '{{ $size }}')"
                                class="px-4 py-2 text-sm border {{ $selectedSize === $size ? 'bg-black text-white border-black' : 'border-black/30 hover:border-black' }}">
                                {{ $size }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($this->colors->isNotEmpty())
                <div class="mb-5">
                    <p class="text-xs uppercase tracking-wide font-medium mb-2">{{ __('Couleur') }}</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($this->colors as $color)
                            <button type="button" wire:click="$set('selectedColor', '{{ $color }}')"
                                class="px-4 py-2 text-sm border {{ $selectedColor === $color ? 'bg-black text-white border-black' : 'border-black/30 hover:border-black' }}">
                                {{ $color }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mb-6 text-sm">
                @if ($this->selectedVariant)
                    @if ($this->selectedVariant->stock > 0)
                        <span class="text-green-700">{{ $this->selectedVariant->stock <= 5 ? __('Plus que :count en stock', ['count' => $this->selectedVariant->stock]) : __('En stock') }}</span>
                    @else
                        <span class="text-red-600">{{ __('Rupture de stock') }}</span>
                    @endif
                @elseif ($this->sizes->isNotEmpty() || $this->colors->isNotEmpty())
                    <span class="text-black/50">{{ __('Choisis une option pour voir la disponibilité') }}</span>
                @endif
            </div>

            @if ($errorMessage)
                <p class="text-sm text-red-600 mb-4">{{ $errorMessage }}</p>
            @endif

            <div class="flex items-center gap-3 mb-4">
                <span class="text-xs uppercase tracking-wide font-medium">{{ __('Quantité') }}</span>
                <div class="flex items-center border border-black">
                    <button type="button" wire:click="$set('quantity', {{ max(1, $quantity - 1) }})" class="w-10 h-10 flex items-center justify-center">−</button>
                    <span class="w-10 text-center">{{ $quantity }}</span>
                    <button type="button" wire:click="$set('quantity', {{ $quantity + 1 }})" class="w-10 h-10 flex items-center justify-center">+</button>
                </div>
            </div>

            @php $unavailable = ! $this->selectedVariant || $this->selectedVariant->stock < 1; @endphp

            <button type="button" wire:click="orderNow" @if ($unavailable) disabled @endif
                class="w-full h-12 bg-black text-white text-sm font-bold uppercase tracking-wide disabled:opacity-30 disabled:cursor-not-allowed hover:opacity-90 mb-3">
                {{ __('Commander maintenant') }}
            </button>

            <button type="button" wire:click="addToCart" @if ($unavailable) disabled @endif
                class="w-full h-11 border border-black text-black text-sm font-medium uppercase tracking-wide disabled:opacity-30 disabled:cursor-not-allowed hover:bg-black hover:text-white transition-colors mb-6">
                {{ __('Ajouter au panier') }}
            </button>

            @if ($product->description)
                <div class="prose prose-sm max-w-none border-t border-black/10 pt-6">
                    {!! $product->description !!}
                </div>
            @endif
        </div>
    </div>
</div>
