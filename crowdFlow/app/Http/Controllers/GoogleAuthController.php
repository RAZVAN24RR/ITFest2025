<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller
{
    /**
     * Redirecționează utilizatorul către pagina de autentificare Google.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Gestionează callback-ul de la Google.
     */
    public function handleGoogleCallback()
    {
        try {
            // Folosim stateless pentru a evita problemele legate de sesiune
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            // În caz de eroare, redirecționăm către '/' cu mesaj de eroare
            return redirect('/')->with('error', 'A apărut o problemă la autentificare, te rugăm să încerci din nou!');
        }

        // Caută utilizatorul după google_id
        $user = User::where('google_id', $googleUser->getId())->first();

        // Dacă utilizatorul nu există, creăm unul cu numele și google_id-ul, setând expirationPRO la 1 ianuarie 2024
        if (!$user) {
            $user = User::create([
                'name'          => $googleUser->getName() ?: $googleUser->getNickname(),
                'google_id'     => $googleUser->getId(),
                'expirationPRO' => Carbon::create(2024, 1, 1, 0, 0, 0)
            ]);
        }

        // Autentificăm utilizatorul
        Auth::login($user, true);

        // Verificăm dacă este setat flag-ul de plată reușită în sesiune și activăm abonamentul PRO
        if (session('payment_success')) {
            $user->activateProSubscription();
            session()->forget('payment_success');
        }

        // Redirecționează utilizatorul către dashboard
        return redirect()->intended('/dashboard');
    }
}
