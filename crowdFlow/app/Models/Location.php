<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Location extends Model
{
    // Specificăm conexiunea MongoDB și colecția folosită
    protected $connection = 'mongodb';
    protected $collection = 'locations';

    // Definim câmpurile pe care le putem atribui în masă
    protected $fillable = [
        '_id',
        'capacity',
        'count',
    ];
}
