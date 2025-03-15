<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
            // În caz de eroare, redirecționează înapoi cu un mesaj de eroare
            return redirect()->route('login')->with('error', 'A apărut o problemă la autentificare, te rugăm să încerci din nou!');
        }

        // Căutăm utilizatorul după adresa de email
        $user = User::where('email', $googleUser->getEmail())->first();

        // Dacă utilizatorul nu există, îl creăm
        if (!$user) {
            $user = User::create([
                'name'     => $googleUser->getName() ?? $googleUser->getNickname(),
                'email'    => $googleUser->getEmail(),
                // Deoarece se va conecta cu Google, putem seta o parolă aleatorie
                'password' => bcrypt(Str::random(16)),
            ]);
        }

        // Conectăm utilizatorul și ne asigurăm că sesiunea este salvată
        Auth::login($user, true);

        // Redirecționăm utilizatorul către dashboard sau pagina dorită
        return redirect()->intended('/dashboard');
    }
}
