<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Carbon\Carbon;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    /**
     * Specificăm conexiunea MongoDB și colecția utilizată.
     */
    protected $connection = 'mongodb';
    protected $collection = 'users';

    /**
     * Câmpurile pe care le putem atribui în masă.
     */
    protected $fillable = [
        '_id',
        'name',
        'email',
        'google_id',
        'picture',
        'expirationPRO'
    ];

    /**
     * Ascundem câmpurile sensibile din serializare.
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * Convertim câmpul expirationPRO la tipul datetime (utilizând Carbon).
     */
    protected $casts = [
        'expirationPRO' => 'datetime'
    ];

    /**
     * Activează abonamentul PRO.
     * După ce utilizatorul finalizează plata pe Stripe sau se autentifică după plată,
     * se apelează această metodă pentru a seta data expirării la o lună de la momentul curent.
     */
    public function activateProSubscription()
    {
        $this->expirationPRO = Carbon::now()->addMonthNoOverflow();
        $this->save();
    }

    /**
     * Resetează abonamentul PRO.
     * Dacă utilizatorul se loghează normal (fără a accesa secțiunea PRO),
     * stabilim o dată fixă, de exemplu, 1 ianuarie 2024 la miezul nopții.
     */
    public function clearProSubscription()
    {
        $this->expirationPRO = Carbon::create(2024, 1, 1, 0, 0, 0);
        $this->save();
    }

    /**
     * Verifică dacă abonamentul PRO este activ.
     *
     * @return bool True dacă expirationPRO este setat și este în viitor.
     */
    public function isPro()
    {
        return !is_null($this->expirationPRO) && $this->expirationPRO->isFuture();
    }
}
