<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Geobra extends Model
{
    use HasFactory;

    protected $table = 'geo_obra';

    protected $fillable = [
        'obras',
        'provincia',
        'distrito',
        'departamento',
        'coordenadas',
    ];
}