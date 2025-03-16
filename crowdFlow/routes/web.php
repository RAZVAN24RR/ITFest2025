<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\GoogleAuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('login.google.callback');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Ruta de login redirecționează către '/'; în cazul în care nu ești autentificat,
// de obicei pagina principală conține și opțiunea de a te autentifica prin Google.
Route::get('/login', function() {
    return redirect('/');
})->name('login');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::get('/locations/{id}', [LocationController::class, 'show']);

// Rutele pentru Stripe Checkout
Route::get('/stripe/checkout', [StripeController::class, 'checkout'])->name('stripe.checkout');
Route::get('/checkout/success', [StripeController::class, 'checkoutSuccess'])->name('checkout.success');
Route::get('/checkout/cancel', [StripeController::class, 'checkoutCancel'])->name('checkout.cancel');
