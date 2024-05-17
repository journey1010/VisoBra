<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

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

    protected function coordenadas(): Attribute
    {
        return Attribute::make(
            set : fn (array $value) => ("POINT({$value['x']}, {$value['y']})"),
        );
    }

}