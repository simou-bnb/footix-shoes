@php $pixelData = json_encode(['value'=>(float)($subtotal??0),'currency'=>'DZD','num_items'=>(int)($items?$items->sum('quantity'):0)]); @endphp
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-8 sm:py-12"
    x-data="{ fired: false }"
    x-init="
        if (!fired) {
            console.log('Triggering InitiateCheckout via Alpine', {{ $pixelData }});
            if (typeof fbq !== 'undefined') fbq('track', 'InitiateCheckout', {{ $pixelData }});
            fired = true;
        }
    ">
    <h1 class="text-3xl font-extrabold uppercase tracking-tight mb-8 border-b border-black pb-6">{{ __('Commander') }}</h1>

    @if ($items->isEmpty())
        <div class="text-center py-16">
            <p class="text-black/60 mb-6">{{ __('Ton panier est vide.') }}</p>
            <a href="{{ route('home') }}" wire:navigate class="inline-block px-6 py-3 bg-black text-white text-sm font-medium uppercase tracking-wide">
                {{ __('Continuer mes achats') }}
            </a>
        </div>
    @else
        @error('cart')
            <div class="mb-6 border border-red-600 bg-red-50 text-red-700 px-4 py-3 text-sm">{{ $message }}</div>
        @enderror

        <form wire:submit="placeOrder" class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="absolute -left-[9999px] w-px h-px overflow-hidden" aria-hidden="true">
                <label for="website">{{ __('Ne pas remplir ce champ') }}</label>
                <input type="text" id="website" wire:model="website" tabindex="-1" autocomplete="off">
            </div>

            <div class="lg:col-span-2 space-y-5">
                <div>
                    <label class="block text-xs uppercase tracking-wide font-medium mb-1">{{ __('Nom complet') }}</label>
                    <input type="text" wire:model="customerName" class="w-full border border-black/30 focus:border-black px-3 py-2 outline-none">
                    @error('customerName') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs uppercase tracking-wide font-medium mb-1">{{ __('Téléphone') }}</label>
                    <input type="tel" wire:model="customerPhone" class="w-full border border-black/30 focus:border-black px-3 py-2 outline-none" placeholder="05XX XX XX XX">
                    @error('customerPhone') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs uppercase tracking-wide font-medium mb-1">{{ __('Wilaya') }}</label>
                        <select wire:model.live="wilayaId" class="w-full border border-black/30 focus:border-black px-3 py-2 outline-none bg-white">
                            <option value="">{{ __('Choisir...') }}</option>
                            @foreach ($wilayas as $wilaya)
                                <option value="{{ $wilaya->id }}">{{ $wilaya->code }} - {{ $wilaya->name }}</option>
                            @endforeach
                        </select>
                        @error('wilayaId') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wide font-medium mb-1">{{ __('Commune') }}</label>
                        <input type="text" wire:model="commune" class="w-full border border-black/30 focus:border-black px-3 py-2 outline-none">
                        @error('commune') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                @if ($this->selectedWilaya)
                    <div>
                        <label class="block text-xs uppercase tracking-wide font-medium mb-2">{{ __('Livraison') }}</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center justify-between border px-4 py-3 cursor-pointer {{ $deliveryType === 'home' ? 'border-black bg-black text-white' : 'border-black/30' }}">
                                <span class="flex items-center gap-2">
                                    <input type="radio" wire:model.live="deliveryType" value="home" class="accent-white">
                                    {{ __('À domicile') }}
                                </span>
                                <span class="text-sm" dir="ltr">{{ number_format($this->selectedWilaya->home_delivery_price, 0, ',', ' ') }} DA</span>
                            </label>

                            @if ($this->selectedWilaya->hasStopDesk())
                                <label class="flex items-center justify-between border px-4 py-3 cursor-pointer {{ $deliveryType === 'stopdesk' ? 'border-black bg-black text-white' : 'border-black/30' }}">
                                    <span class="flex items-center gap-2">
                                        <input type="radio" wire:model.live="deliveryType" value="stopdesk" class="accent-white">
                                        {{ __('Stop Desk') }}
                                    </span>
                                    <span class="text-sm" dir="ltr">{{ number_format($this->selectedWilaya->stopdesk_delivery_price, 0, ',', ' ') }} DA</span>
                                </label>
                            @endif
                        </div>
                        @error('deliveryType') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif

                @if ($deliveryType === 'home')
                    <div>
                        <label class="block text-xs uppercase tracking-wide font-medium mb-1">{{ __('Adresse') }}</label>
                        <textarea wire:model="address" rows="2" class="w-full border border-black/30 focus:border-black px-3 py-2 outline-none"></textarea>
                        @error('address') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                @endif
            </div>

            <div class="lg:col-span-1">
                <div class="border border-black p-5 sticky top-24">
                    <h2 class="text-sm font-bold uppercase tracking-wide mb-4">{{ __('Récapitulatif') }}</h2>

                    <div class="space-y-3 mb-4 max-h-64 overflow-y-auto">
                        @foreach ($items as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-black/70">{{ $item->variant->product->name }} ({{ $item->variant->label }}) &times;{{ $item->quantity }}</span>
                                <span class="shrink-0 ms-2" dir="ltr">{{ number_format($item->lineTotal, 0, ',', ' ') }} DA</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-black/10 pt-3 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span>{{ __('Sous-total') }}</span>
                            <span dir="ltr">{{ number_format($subtotal, 0, ',', ' ') }} DA</span>
                        </div>
                        <div class="flex justify-between">
                            <span>{{ __('Livraison') }}</span>
                            <span dir="ltr">{{ $this->selectedWilaya ? number_format($this->deliveryPrice, 0, ',', ' ') . ' DA' : '—' }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-base border-t border-black/10 pt-2">
                            <span>{{ __('Total') }}</span>
                            <span dir="ltr">{{ number_format($subtotal + $this->deliveryPrice, 0, ',', ' ') }} DA</span>
                        </div>
                    </div>

                    <button type="submit" class="mt-5 w-full py-3 bg-black text-white text-sm font-medium uppercase tracking-wide hover:opacity-90">
                        {{ __('Valider la commande') }}
                    </button>
                    <p class="text-xs text-black/50 mt-3 text-center">{{ __('Paiement en espèces à la livraison.') }}</p>
                </div>
            </div>
        </form>
    @endif
</div>
