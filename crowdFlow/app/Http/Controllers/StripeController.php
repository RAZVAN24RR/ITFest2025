<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function checkout(Request $request)
    {
        // Setează cheia secretă din .env
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Creează un checkout session pentru plata de 15 USD (unit_amount este în cenți: 1500 = 15.00 USD)
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
}
