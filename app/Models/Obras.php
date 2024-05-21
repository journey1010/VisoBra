<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;

class Obras extends Model
{
    use HasFactory;

    protected $table = 'obras';

    protected $fillable = [
        'funcion_id',
        'programa_id',
        'subprograma_id',
        'sector_id',
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

    protected function fechaRegistro(): Attribute
    {
        return Attribute::make(
            set: fn (string|null $value) => jsonDateToPhp($value),
        );
    }

    protected function fechaViabilidad(): Attribute
    {
        return Attribute::make(
            set : fn(string|null $value) => jsonDateToPhp($value),
        );
    }

    protected function descripcionAlternativa(): Attribute
    {
        return Attribute::make(
            set: fn (string|null $value) => clearRichText($value),
        );
    }

    protected function añoMesPrimerDevengado(): Attribute
    {
        return Attribute::make(
            set: fn(string|null $value) => $this->convertDate($value),
        );
    }

    protected function añoMesUltimoDevengado(): Attribute
    {
        return Attribute::make(
            set: fn(string|null $value) => $this->convertDate($value),
        );
    }

    /**
     * Convert string of date type (Ym) a validate date of type Y-m
     * @return date Y-m
     */
    private function conmainObrasvertDate(string|null $date)
    {
        if(!$date){
            return null;
        }
        $dateConvert = date_create_from_format('Ym', $date);
        return $dateConvert->format('Y-m');
    }

    /**
     * Busqueda por codigo unico de inversion, snip, nombre, provincia, distrito
    */

    public static function searchPaginate(
        ?int $page = 1, 
        ?int $itemsPerPage = 20,
        ?string $estadoInversion = null,
        ?string $funcion = null,
        ?string $subprograma = null,
        ?string $programa = null,
        ?string $sector = null, 
        ?int $codeUnique = null, 
        ?int $snip =  null, 
        ?string $nombreObra = null,
        ?string $provincia = null, 
        ?string $nivelGobierno = null, 
        ?string $distrito = null, 
    ){
        $query  = DB::table('obras as o')
            ->select(
                'o.id as id',
                'o.codigo_unico_inversion as codigoUnicoInversion', 
                'o.codigo_snip',
                'o.nombre_inversion as nombreInversion',
                'o.estado_inversion as estadoInversion',
                'o.monto_viable as montoViable',
                'f.nombre as funcion',
                'sub.nombre as subprograma',
                'p.nombre as programa',
                's.nombre as sector'            )
            ->join('programa as p','o.programa_id', '=', 'p.id')
            ->join('sector as s', 'o.sector_id', '=', 's.id')
            ->join('subprograma as sub', 'o.subprograma_id', '=', 'sub.id')
            ->join('funcion as f', 'o.funcion_id', '=', 'f.id')
            ->leftJoin('geo_obra as g', 'o.id', '=', 'g.obras_id');

        if ($estadoInversion) {
            $query->where('o.estado_inversion', '=', $estadoInversion);
        }

        if ($funcion) {
            $query->where('f.nombre', 'LIKE', "%$funcion%");
        }

        if ($subprograma) {
            $query->where('sub.nombre', 'LIKE', "%$subprograma%");
        }

        if ($programa) {
            $query->where('p.nombre', 'LIKE', "%$programa%");
        }

        if ($sector) {
            $query->where('s.nombre', 'LIKE', "%$sector%");
        }

        if ($codeUnique) {
            $query->where('o.codigo_unico_inversion', '=', $codeUnique);
        }

        if ($snip) {
            $query->where('o.codigo_snip', '=', $snip);
        }

        if ($nivelGobierno) {
            $query->where('o.nivel_gobierno', 'LIKE', "%$nivelGobierno%");
        }

        if ($provincia) {
            $query->where('g.provincia', 'LIKE', "%$provincia%");
        }

        if ($distrito) {
            $query->where('g.distrito', 'LIKE', "%$distrito%");
        }

        if ($nombreObra) {
            $query->whereRaw('MATCH(o.nombre_inversion) AGAINST(?)', [$nombreObra]);
            // $query->orderBy('relevancia', 'desc');
        }

        $offset = ($page - 1) * $itemsPerPage;
        $results = $query->offset($offset)->limit($itemsPerPage)->get();

        return $results;
    }
}