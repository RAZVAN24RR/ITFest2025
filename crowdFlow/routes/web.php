<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\StripeController;

Route::get('/', function () {
    return view('welcome');
});use App\Http\Controllers\GoogleAuthController;

Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('login.google.callback');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/login', function() {
    return view('auth.login');
})->name('login');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

Route::get('/locations/{id}', [LocationController::class, 'show']);

Route::get('/stripe/checkout', [StripeController::class, 'checkout'])->name('stripe.checkout');

// Rute pentru redirecționările după plată (poți personaliza textul sau pagina)
Route::get('/checkout/success', function () {
    return 'Payment Success';
})->name('checkout.success');

Route::get('/checkout/cancel', function () {
    return 'Payment Cancelled';
})->name('checkout.cancel');
