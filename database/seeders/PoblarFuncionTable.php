<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoblarFuncionTable extends Seeder
{
    /**
     * Run the database seeds.
     */

    protected $campos = [
        'TRANSPORTE',
        'SALUD Y SANEAMIENTO',
        'ENERGIA Y RECURSOS MINERALES',
        'EDUCACION Y CULTURA',
        'COMUNICACIONES',
        'INDUSTRIA, COMERCIO Y SERVICIOS',
        'AGRARIA',
        'JUSTICIA',
        'VIVIENDA Y DESARROLLO URBANO',
        'ADMINISTRACION Y PLANEAMIENTO',
        'ASISTENCIA Y PREVISION SOCIAL',
        'DEFENSA Y SEGURIDAD NACIONAL',
        'PESCA'
    ];

    public function run(): void
    {
        date_default_timezone_set('America/Lima');
        foreach($this->campos as $key){
            DB::table('funcion')->insert([
                'nombre' => $key,
                'created_at' => date('Y-m-d :H:i:s'),
            ]);
        }
    }
}
