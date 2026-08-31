<?php

use App\Livewire\Storefront\CartPage;
use App\Livewire\Storefront\CategoryListing;
use App\Livewire\Storefront\Checkout;
use App\Livewire\Storefront\Home;
use App\Livewire\Storefront\OrderConfirmation;
use App\Livewire\Storefront\PrivacyPolicy;
use App\Livewire\Storefront\ProductDetail;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');
Route::get('/categorie/{category:slug}', CategoryListing::class)->name('category.show');
Route::get('/produit/{product:slug}', ProductDetail::class)->name('product.show');
Route::get('/panier', CartPage::class)->name('cart');
Route::get('/commander', Checkout::class)->name('checkout');
Route::get('/commande/confirmation/{order:order_number}', OrderConfirmation::class)->name('order.confirmation');
Route::get('/politique-de-confidentialite', PrivacyPolicy::class)->name('privacy');

Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['fr', 'ar'])) {
        session()->put('locale', $locale);
    }
    return back();
})->name('lang.switch');
