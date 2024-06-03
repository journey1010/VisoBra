<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metadata extends Model
{
    use HasFactory;

    protected $table = 'metadata_list_obras';

    protected $fillable = [
        'pages_size',
        'total_rows',
        'total_pages',
        'endpoint_name'
    ];

    /**
     * Actualiza la  única fila que existe. 
     */
    
    public static function upMetada(int $id, int $pageSize, int $totalRows, int $totalPage, string $endpointName): void
    {
        self::where('id'  , $id)
            ->update([
                'pages_size' => $pageSize,
                'total_rows' => $totalRows,
                'total_pages' => $totalPage,
                'endpoint_name' => $endpointName
            ]);
    } 
}