<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoblarProgramaTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    protected $campos = [
        'TRANSPORTE TERRESTRE',
        'SANEAMIENTO',
        'ENERGIA',
        'EDUCACION PRIMARIA',
        'TRANSPORTE HIDROVIARIO',
        'SALUD INDIVIDUAL',
        'TELECOMUNICACIONES',
        'TURISMO',
        'COMERCIO',
        'TRANSPORTE AEREO',
        'PRESERVACION DE LOS RECURSOS NATURALES RENOVABLES',
        'JUSTICIA',
        'DESARROLLO URBANO',
        'TRANSPORTE METROPOLITANO',
        'INFRAESTRUCTURA EDUCATIVA',
        'EDUCACION SUPERIOR',
        'ADMINISTRACION',
        'PROMOCION Y ASISTENCIA SOCIAL COMUNITARIA',
        'EDUCACION FISICA Y DEPORTES',
        'PROMOCION Y EXTENSION RURAL',
        'PROTECCION DEL MEDIO AMBIENTE',
        'INDUSTRIA',
        'PLANEAMIENTO GUBERNAMENTAL',
        'EDUCACION ESPECIAL',
        'DEFENSA CONTRA SINIESTROS',
        'ORDEN INTERNO',
        'PROMOCION DE LA PRODUCCION PECUARIA',
        'SALUD COLECTIVA',
        'PROMOCION DE LA PRODUCCION PESQUERA',
        'EDUCACION SECUNDARIA',
        'EDUCACION INICIAL',
        'CIENCIA Y TECNOLOGIA',
        'PROMOCION DE LA PRODUCCION AGRARIA',
        'VIVIENDA',
        'ASISTENCIA A EDUCANDOS',
        'CULTURA',
    ];


    public function run(): void
    {
        date_default_timezone_set('America/Lima');
        foreach($this->campos as $key){
            DB::table('programa')->insert([
                'nombre' => $key,
                'created_at' => date('Y-m-d :H:i:s'),
            ]);
        }
    }
} 