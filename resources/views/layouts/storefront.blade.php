<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <meta name="description" content="{{ $description ?? 'Footix Shoes — chaussures, vêtements et accessoires en Algérie. Paiement à la livraison.' }}">
    <meta name="facebook-domain-verification" content="a77pj5zcl1okvtn4266viu09aty6j5" />

    {{-- Open Graph Meta Tags --}}
    <meta property="og:title" content="{{ $title ?? config('app.name') }}" />
    <meta property="og:description" content="{{ $description ?? 'Footix Shoes — chaussures, vêtements et accessoires en Algérie.' }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ $ogUrl ?? request()->url() }}" />
    @if(isset($ogImage))
        <meta property="og:image" content="{{ $ogImage }}" />
    @else
        <meta property="og:image" content="{{ asset('images/logo.png') }}" />
    @endif

    {{-- Meta Pixel Code --}}
    @if(config('services.meta.pixel_id'))
        <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
        n.callMethod.apply(n,arguments):n.queue.push(arguments)};
        if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
        n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];
        s.parentNode.insertBefore(t,s)}(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ config('services.meta.pixel_id') }}');
        fbq('track', 'PageView');

        // Also fire PageView on each SPA navigation
        document.addEventListener('livewire:navigated', function () {
            fbq('track', 'PageView');
        });
        </script>
        <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id={{ config('services.meta.pixel_id') }}&ev=PageView&noscript=1" /></noscript>
    @endif
    {{-- End Meta Pixel Code --}}

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen flex flex-col bg-white text-black antialiased">

    <header class="sticky top-0 z-40 bg-white border-b border-black" x-data="{ mobileOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center h-full py-1">
                    <img src="{{ asset('images/logo.png') }}" alt="Footix Shoes" class="h-full w-auto object-contain">
                </a>

                <nav class="hidden md:flex items-center gap-8 text-sm font-medium uppercase tracking-wide">
                    @foreach (\App\Models\Category::query()->where('is_active', true)->orderBy('sort_order')->get() as $navCategory)
                        <a href="{{ route('category.show', $navCategory) }}" wire:navigate class="hover:opacity-60 transition-opacity">
                            {{ $navCategory->name }}
                        </a>
                    @endforeach
                </nav>

                <div class="flex items-center gap-2">
                    @livewire('storefront.cart-counter')

                    <div class="hidden md:flex items-center gap-2 text-xs font-bold uppercase tracking-wide border-s border-black ps-4 ms-2">
                        <a href="{{ route('lang.switch', 'fr') }}" wire:navigate class="{{ app()->getLocale() === 'fr' ? 'text-black' : 'text-black/40 hover:text-black transition-colors' }}">FR</a>
                        <span>/</span>
                        <a href="{{ route('lang.switch', 'ar') }}" wire:navigate class="{{ app()->getLocale() === 'ar' ? 'text-black' : 'text-black/40 hover:text-black transition-colors' }}">عربي</a>
                    </div>

                    <button type="button" class="md:hidden inline-flex items-center justify-center w-10 h-10" @click="mobileOpen = !mobileOpen" aria-label="Menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                            <path x-show="mobileOpen" x-cloak stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <nav x-show="mobileOpen" x-cloak class="md:hidden flex flex-col gap-4 pb-4 text-sm font-medium uppercase tracking-wide">
                @foreach (\App\Models\Category::query()->where('is_active', true)->orderBy('sort_order')->get() as $navCategoryMobile)
                    <a href="{{ route('category.show', $navCategoryMobile) }}" wire:navigate @click="mobileOpen = false">
                        {{ $navCategoryMobile->name }}
                    </a>
                @endforeach
                <div class="flex items-center gap-4 pt-4 mt-2 border-t border-black/10">
                    <a href="{{ route('lang.switch', 'fr') }}" wire:navigate class="{{ app()->getLocale() === 'fr' ? 'text-black font-bold' : 'text-black/50' }}">Français</a>
                    <a href="{{ route('lang.switch', 'ar') }}" wire:navigate class="{{ app()->getLocale() === 'ar' ? 'text-black font-bold' : 'text-black/50' }}">العربية</a>
                </div>
            </nav>
        </div>
    </header>

    <main class="flex-1">
        {{ $slot }}
    </main>

    <footer class="bg-black text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 text-sm">
            <p class="text-lg font-extrabold tracking-widest uppercase mb-2">Footix <span class="font-light">Shoes</span></p>
            <p class="text-white/60">{{ __("Chaussures, vêtements & accessoires — livraison dans toute l'Algérie, paiement à la réception.") }}</p>
            <div class="mt-4 flex flex-wrap gap-4">
                <a href="{{ route('privacy') }}" wire:navigate class="text-white/40 hover:text-white/70 transition-colors">{{ __('Politique de confidentialité') }}</a>
            </div>
            <p class="text-white/40 mt-6">&copy; {{ now()->year }} Footix Shoes.</p>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
