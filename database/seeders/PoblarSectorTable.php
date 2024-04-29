<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoblarSectorTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    protected $campos = [
        'AGRICULTURA Y RIEGO',
        'VIVIENDA, CONSTRUCCION Y SANEAMIENTO',
        'ENERGIA Y MINAS',
        'EDUCACION',
        'TRANSPORTES Y COMUNICACIONES',
        'GOBIERNOS REGIONALES',
        'GOBIERNOS LOCALES',
        'JUSTICIA',
        'MINISTERIO PUBLICO',
        'PRESIDENCIA DEL CONSEJO DE MINISTROS',
        'PODER JUDICIAL',
        'DEFENSA',
        'ECONOMIA Y FINANZAS',
        'COMERCIO EXTERIOR Y TURISMO',
        'INTERIOR',
        'PRODUCCION',
        'DESARROLLO E INCLUSION SOCIAL',
        'UNIVERSIDADES',
    ];

    public function run(): void
    {
        date_default_timezone_set('America/Lima');
        foreach($this->campos as $key){
            DB::table('sector')->insert([
                'nombre' => $key,
                'created_at' => date('Y-m-d :H:i:s'),
            ]);
        }
    }
}
