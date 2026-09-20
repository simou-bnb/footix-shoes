<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS pour corriger les bugs de proxy sur l'hébergement LWS
        if (config('app.env') !== 'local') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \BezhanSalleh\LanguageSwitch\LanguageSwitch::configureUsing(function (\BezhanSalleh\LanguageSwitch\LanguageSwitch $switch) {
            $switch->locales(['fr', 'ar']);
        });
    }
}
