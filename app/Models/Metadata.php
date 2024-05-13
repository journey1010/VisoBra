<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metadata extends Model
{
    use HasFactory;

    protected $fillable = [
        'pages_size',
        'total_rows',
        'total_pages',
    ];

    /**
     * Actualiza la  única fila que existe. 
     */
    
    public static function upMetada(int $id, int $pageSize, int $totalRows, int $totalPage): void
    {
        self::where('id'  , $id)
            ->update([
                'pages_size' => $pageSize,
                'total_rows' => $totalRows,
                'total_pages' => $totalPage
            ]);
    } 
}