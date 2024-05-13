<?php

namespace App\Services;

use App\Services\Contracts\DataHandler; 

class ObrasEndpoint implements DataHandler
{


     /** 
      *Propiedades a convertir
      * FechaRegistro 
      * FechaViabilidad
      * MesAnioPDev
      * MesAnioUDev
      *
      *
      *      
      */
    protected $dataHoped = [
        'Funcion' => 'funcion_id',
        'Programa' => 'programa_id',
        'Subprograma' => 'subprograma_id',
        'Sector' => 'sector_id',
        'CodigoUnico' => 'codigo_unico_inversion',
        'Codigo' => 'codigo_snip',
        'Nombre' => 'nombre_inversion',
        'MontoAlternativa' => 'monto_viable',
        'Situacion' => 'situacion',
        'Estado' => 'estado_inversion',
        'Nivel' => 'nivel_gobierno',
        'Pliego' => 'entidad',
        'Opmi' => 'unidad_opmi',
        'ResponsableOpmi' => 'responsable_opmi',
        'Uei' => 'unidad_uei',
        'ResponsableUei' => 'responsable_uei',
        'Uf' => 'unidad_uf',
        'ResponsableUf' => 'responsable_uf',
        'Opi' => 'entidad_opi',
        'ResponsableOpi' => 'responsable_opi',
        'Ejecutora' => 'ejecutora',
        'FechaRegistro' => 'fecha_registro',
        'UltimoEstudio' => 'ultimo_estudio',
        'EstadoEstudio' => 'estado_estudio',
        'NivelViabilidad' => 'nivel_viabilidad',
        'ResponsableViabilidad' => 'responsable_viabilidad',
        'FechaViabilidad' => 'fecha_viabilidad',
        'Costo' => 'costo_actualizado',
        'Alternativa' => 'descripcion_alternativa',
        'Beneficiarios' => 'beneficiaros_habitantes',
        'DevActual'=>'devengado_año_vigente',
        'DevAcumuladoAnterior'=>'devengado_año_anterior',        
        'PimActual'=>'pim_año_vigente',
        'DevAcumulado'=>'devengado_acumulado',        
        'Marco'=>'marco',        
        'SaldoPorFinanciar'=>'saldo_por_financiar',
        'MesAnioPDev'=>'año_mes_primer_devengado',        
        'MesAnioUDev'=>'año_mes_ultimo_devengado',
        'IncluidoProgramacionPmi'=>'incluido_programacion_pmi',
        'IncluidoEjecucionPmi'=>'incluido_ejecucion_pmi',
        'GanadorFoniprel'=>'ganador_fronipel',
        'DescripcionCierre'=>'registro_cierre',
    ];

    public function validateFormat(): bool
    {
     
        return true;
    }

    public function proccesData()
    {
        
    }   
    
    public function store()
    {
        
    }

}
