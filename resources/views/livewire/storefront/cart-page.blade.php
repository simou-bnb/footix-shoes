<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <h1 class="text-3xl font-extrabold uppercase tracking-tight mb-8 border-b border-black pb-6">Panier</h1>

    @if ($items->isEmpty())
        <div class="text-center py-16">
            <p class="text-black/60 mb-6">Ton panier est vide.</p>
            <a href="{{ route('home') }}" wire:navigate class="inline-block px-6 py-3 bg-black text-white text-sm font-medium uppercase tracking-wide">
                Continuer mes achats
            </a>
        </div>
    @else
        <div class="divide-y divide-black/10">
            @foreach ($items as $item)
                <div class="flex flex-wrap items-center justify-between gap-4 py-5" wire:key="cart-item-{{ $item->variant->id }}">
                    @php $image = $item->variant->product->images->first(); @endphp
                    <div class="flex items-center gap-4 flex-1 min-w-[220px]">
                        <div class="w-20 h-20 bg-neutral-100 overflow-hidden shrink-0">
                            @if ($image)
                                <img src="{{ Storage::disk('public')->url($image->path) }}" alt="{{ $item->variant->product->name }}" class="w-full h-full object-cover">
                            @endif
                        </div>

                        <div class="min-w-0">
                            <p class="font-medium uppercase text-sm tracking-wide truncate">{{ $item->variant->product->name }}</p>
                            <p class="text-sm text-black/50">{{ $item->variant->label }}</p>
                            <p class="text-sm mt-1">{{ number_format($item->unitPrice, 0, ',', ' ') }} DA</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <div class="flex items-center border border-black shrink-0">
                            <button type="button" wire:click="updateQuantity({{ $item->variant->id }}, {{ $item->quantity - 1 }})" class="w-8 h-8 flex items-center justify-center">−</button>
                            <span class="w-8 text-center text-sm">{{ $item->quantity }}</span>
                            <button type="button" wire:click="updateQuantity({{ $item->variant->id }}, {{ $item->quantity + 1 }})" class="w-8 h-8 flex items-center justify-center">+</button>
                        </div>

                        <p class="w-20 text-right font-medium shrink-0">{{ number_format($item->lineTotal, 0, ',', ' ') }} DA</p>

                        <button type="button" wire:click="removeItem({{ $item->variant->id }})" class="text-black/40 hover:text-black shrink-0" aria-label="Retirer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex items-center justify-between border-t border-black pt-6">
            <span class="text-lg font-medium uppercase tracking-wide">Sous-total</span>
            <span class="text-xl font-bold">{{ number_format($subtotal, 0, ',', ' ') }} DA</span>
        </div>
        <p class="text-sm text-black/50 mt-1">Frais de livraison calculés à l'étape suivante selon ta wilaya.</p>

        <a href="{{ route('checkout') }}" wire:navigate class="mt-6 block text-center w-full py-4 bg-black text-white font-medium uppercase tracking-wide hover:opacity-90">
            Passer la commande
        </a>
    @endif
</div>
