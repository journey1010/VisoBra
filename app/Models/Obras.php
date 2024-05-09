<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obras extends Model
{
    use HasFactory;

    protected $table = 'obras';

    protected $fillable = [
        'funcion_id',
        'programa_id',
        'subprograma_id',
        'sector',
        'codigo_unico_inversion',
        'codigo_snip', 
        'nombre_inversion',
        'monto_viable',
        'situacion',
        'estado_inversion',
        'nivel_gobierno',
        'entidad',
        'unidad_opmi',
        'responsable_opmi',
        'unidad_uei',
        'responsable_uei', 
        'responsable_uf',
        'entidad_opi',
        'responsable_opi',
        'ejecutora', 
        'fecha_registro',
        'ultimo_estudio',
        'estado_estudio',
        'nivel_viabilidad',
        'responsable_viabilidad',
        'fecha_viabilidad',
        'costo_actualizado',
        'descripcion_alternativa',
        'beneficiaros_habitantes',
        'devengado_año_vigente',
        'devengado_año_anterior',
        'pim_año_vigente', 
        'devengado_acumulado', 
        'marco',
        'saldo_por_financiar',
        'año_mes_primer_devengado',
        'año_mes_ultimo_devengado',
        'incluido_programacion_pmi',
        'incluido_ejecucion_pmi',
        'ganador_fronipel',
        'registro_cierre',
        'created_at',
        'updated_at'
    ];

}