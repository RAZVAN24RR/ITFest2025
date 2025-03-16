<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Auth;

class StripeController extends Controller
{
    public function checkout(Request $request)
    {
        // Setează cheia secretă Stripe din fișierul .env
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Creează sesiunea de checkout pentru plata de $15 (1500 de cenți)
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => '$15/month for Analytics',
                    ],
                    'unit_amount' => 1500,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('checkout.cancel'),
        ]);

        // Redirecționează utilizatorul către pagina de checkout Stripe
        return redirect($session->url);
    }

    /**
     * Metodă apelată după ce plata este reușită.
     * Dacă utilizatorul NU este autentificat, setează un flag în sesiune și îl redirecționează către login.
     * Dacă este autentificat, activează abonamentul PRO.
     */
    public function checkoutSuccess(Request $request)
    {
        if (!Auth::check()) {
            // Stochează în sesiune flag-ul pentru plată reușită
            session(['payment_success' => true]);
            return redirect()->route('login')
                ->with('message', 'Plata a fost efectuată cu succes! Te rugăm să te autentifici pentru a activa abonamentul PRO.');
        }

        $user = Auth::user();
        // Activează abonamentul PRO (setează expirationPRO la o lună de la momentul curent)
        $user->activateProSubscription();
        return redirect()->route('dashboard')
            ->with('success', 'Abonamentul PRO a fost activat!');
    }

    /**
     * Metodă apelată când plata este anulată.
     */
    public function checkoutCancel(Request $request)
    {
        return redirect()->route('dashboard')
            ->with('error', 'Plata a fost anulată.');
    }
}
