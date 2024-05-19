<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Contrataciones extends Model
{
    use HasFactory;

    protected $table = 'contrataciones_obra';

    protected $fillable = [
        'obra_id',
        'contrataciones',
    ];

    // protected function casts(): array
    // {
    //     return [
    //         'contrataciones' => 'array',
    //     ];
    // }

    protected function contrataciones(): Attribute
    {
        return Attribute::make(
            set: fn (array $value) => json_encode($value),
            get: fn (string $value) => json_decode($value, true),
        );
    }
}
