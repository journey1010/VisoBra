<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PoblarTablas extends Seeder
{
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

    protected $campos2 = [
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

    protected $campos3 = [
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


    protected $campos4 = [
        'CONSTRUCCION Y MEJORAMIENTO DE CARRETERAS',
        'SANEAMIENTO GENERAL',
        'ELECTRIFICACION RURAL',
        'ENSEÑANZA PRIMARIA',
        'CONTROL Y SEGURIDAD DEL TRAFICO HIDROVIARIO',
        'ATENCION MEDICA BASICA',
        'PUERTOS Y  TERMINALES FLUVIALES Y LACUSTRES',
        'CAMINOS RURALES',
        'ATENCION MEDICA ESPECIALIZADA',
        'SERVICIOS DE TELECOMUNICACIONES',
        'PROMOCION DEL TURISMO',
        'COMERCIALIZACION',
        'SERVICIOS DE TRANSPORTE AEREO',
        'PROTECCION DE LA FLORA Y FAUNA',
        'READAPTACION SOCIAL',
        'PLANEAMIENTO URBANO',
        'VIAS URBANAS',
        'DEFENSA DE LOS DERECHOS CONSTITUCIONALES Y LEGALES',
        'EDIFICACIONES ESCOLARES',
        'INFRAESTRUCTURA UNIVERSITARIA',
        'LIMPIEZA PÚBLICA',
        'GENERACION DE ENERGIA ELECTRICA',
        'DISTRIBUCION DE ENERGIA ELECTRICA',
        'ADMINISTRACION GENERAL',
        'PROMOCION Y ASISTENCIA SOCIAL',
        'CENTROS DEPORTIVOS Y RECREATIVOS',
        'ADMINISTRACION DE JUSTICIA',
        'EXTENSION RURAL',
        'CONTROL DE LA CONTAMINACION',
        'EDIFICACIONES PUBLICAS',
        'PROMOCION Y ASISTENCIA COMUNITARIA',
        'TRANSPORTE METROPOLITANO',
        'PRODUCCION INDUSTRIAL',
        'PROMOCION AL COMERCIO',
        'ORGANIZACION Y MODERNIZACION ADMINISTRATIVA',
        'PARQUES Y JARDINES',
        'REHABILITACION DE CARRETERAS',
        'GENERACION DE ENERGIA NO CONVENCIONAL',
        'PROMOCION AGRARIA',
        'EDUCACION COMPENSATORIA',
        'DEFENSA CONTRA INCENDIOS Y EMERGENCIAS MENORES',
        'SUPERIOR UNIVERSITARIA',
        'CONTROL DE ARMAS,MUNICIONES, EXPLOSIVOS DE USO CIVIL Y SERVICIOS DE SEGURIDAD',
        'PROTECCION SANITARIA ANIMAL',
        'DEFENSA CONTRA INUNDACIONES',
        'CONTROL EPIDEMIOLOGICO',
        'FOMENTO DE LA PESCA',
        'OPERACIONES POLICIALES',
        'SEGURIDAD CIUDADANA',
        'REFORESTACION',
        'REGULACION Y CONTROL SANITARIO',
        'FORMACION GENERAL',
        'JARDINES',
        'INFORMATICA',
        'INVESTIGACION APLICADA',
        'CONTROL DE RIESGOS Y DAÑOS PARA LA SALUD',
        'SEMILLAS Y MEJORAMIENTO GENETICO',
        'MECANIZACION AGRICOLA',
        'DESARROLLO DE LA PESCA',
        'DEFENSA CONTRA LA EROSION',
        'SALUD AMBIENTAL',
        'SERVICIOS DE TRANSPORTE TERRESTRE',
        'EDIFICACIONES URBANAS',
        'TRANSMISION DE ENERGIA ELECTRICA',
        'MOVIMIENTOS MIGRATORIOS',
        'APOYO AL ESTUDIANTE',
        'DESARROLLO ANIMAL',
        'SUPERIOR NO UNIVERSITARIA',
        'DIFUSION CULTURAL',
        'REGISTROS',
        'PROMOCION Y DESARROLLO DEPORTIVO',
        'SUPERVISION Y COORDINACION SUPERIOR',
        'RADIODIFUSION',
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

        foreach($this->campos2 as $key){
            DB::table('programa')->insert([
                'nombre' => $key,
                'created_at' => date('Y-m-d :H:i:s'),
            ]);
        }

        foreach($this->campos3 as $key){
            DB::table('sector')->insert([
                'nombre' => $key,
                'created_at' => date('Y-m-d :H:i:s'),
            ]);
        }

        foreach($this->campos4 as $key){
            DB::table('subprograma')->insert([
                'nombre' => $key,
                'created_at' => date('Y-m-d :H:i:s'),
            ]);
        }

        DB::table('users')->insert([
            'name' => 'Lucatiel',
            'last_name' => 'Administrador',
            'dni' => '11111111',
            'email' => 'lucatiel@gmail.com',
            'password' => Hash::make('Hola5.2')  
        ]);
    }

}