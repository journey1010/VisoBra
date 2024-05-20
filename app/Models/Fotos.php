<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Fotos extends Model
{
    use HasFactory;

    protected $table  = 'fotos_obra';

    protected $fillable = [
        'obra_id', 
        'files_path',
    ];

    protected function filesPath(): Attribute
    {
        return Attribute::make(
            set: fn (array $value) => json_encode($value),
            get: fn (string $value) => json_decode($value, true),
        );
    }
}
