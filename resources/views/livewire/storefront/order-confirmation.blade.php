<div class="max-w-2xl mx-auto px-4 sm:px-6 py-12 sm:py-20 text-center">
    <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-black text-white flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
        </svg>
    </div>

    <h1 class="text-2xl sm:text-3xl font-extrabold uppercase tracking-tight mb-2">Merci, {{ $order->customer_name }} !</h1>
    <p class="text-black/60 mb-1">Ta commande a bien été enregistrée.</p>
    <p class="font-medium mb-8">N&deg; {{ $order->order_number }}</p>

    <div class="border border-black text-left p-6 mb-8">
        <p class="text-sm mb-4">
            <strong>On t'appelle au {{ $order->customer_phone }} pour confirmer ta commande</strong> avant l'expédition.
        </p>

        <div class="divide-y divide-black/10">
            @foreach ($order->items as $item)
                <div class="flex justify-between py-2 text-sm">
                    <span>{{ $item->product_name }} @if($item->variant_label && $item->variant_label !== 'Standard') ({{ $item->variant_label }}) @endif &times;{{ $item->quantity }}</span>
                    <span>{{ number_format($item->line_total, 0, ',', ' ') }} DA</span>
                </div>
            @endforeach
        </div>

        <div class="border-t border-black/10 mt-3 pt-3 space-y-1 text-sm">
            <div class="flex justify-between">
                <span>Sous-total</span>
                <span>{{ number_format($order->subtotal, 0, ',', ' ') }} DA</span>
            </div>
            <div class="flex justify-between">
                <span>Livraison ({{ $order->delivery_type->getLabel() }} - {{ $order->wilaya->name }})</span>
                <span>{{ number_format($order->delivery_price, 0, ',', ' ') }} DA</span>
            </div>
            <div class="flex justify-between font-bold text-base pt-1">
                <span>Total</span>
                <span>{{ number_format($order->total, 0, ',', ' ') }} DA</span>
            </div>
        </div>
    </div>

    <a href="{{ route('home') }}" wire:navigate class="inline-block px-6 py-3 bg-black text-white text-sm font-medium uppercase tracking-wide">
        Continuer mes achats
    </a>
</div>
