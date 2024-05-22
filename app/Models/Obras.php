<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

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
            ->selectRaw('
                o.id as id,
                o.codigo_unico_inversion as codigoUnicoInversion, 
                o.codigo_snip,
                o.nombre_inversion as nombreInversion,
                o.estado_inversion as estadoInversion,
                o.monto_viable as montoViable,
                f.nombre as funcion,
                sub.nombre as subprograma,
                p.nombre as programa,
                s.nombre as sector,
                MATCH(o.nombre_inversion) AGAINST(?) as relevancia', [$nombreObra])
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
            $query->orderBy('relevancia', 'desc');
            $itemsPerPage = 3;
        }

        $results = $query->paginate($itemsPerPage, ['*'], 'page', $page);
        $obras = [
            'items' => $results->items(),
            'total_items' => $results->total(),
        ];

        return $obras;
    }

    public static function searchById(int $id)
    {
        $query  = DB::table('obras as o')
        ->selectRaw('
            o.id as id,
            o.codigo_unico_inversion as codigoUnicoInversion, 
            o.codigo_snip,
            o.nombre_inver 
            o.unidad_opmi as unidadOpmi,
            o.responsable_opmi as responsableOpmi,
            o.unidad_uei as unidadUei,
            o.responsable_uei as responsableUei,
            o.unidad_uf as unidadFunciona,
            o.responsable_uf as responsableUf,
            o.entidad_opi as entidadOpi,
            o.responsable_opi as responsableOpi,
            o.ejecutora,
            o.fecha_registro as fechaRegistro,
            o.ultimo_estudio as ultimoEstudio,
            o.estado_estudio as estadoEstudio,
            o.nivel_viabilidad as nivelViabilidad,
            o.responsable_viabilidad as responsableViabilidad,
            o.fecha_viabilidad as fechaViabilidad,
            o.costo_actual _ultimo_devengado as añoMesUltimoDevengado,
            o.incluido_programacion_pmi as incluidoProgramacionPmi,
            o.ganador_fronipel as ganadorFronipel,
            o.registro_cierre as registroCierre,
            c.contrataciones as contrataciones,
            g.provincia as provincia,
            g.distrito as distrito,
            ST_X(g.coordenadas) as longitud,
            ST_Y(g.coordenadas) as latitud,
            foto.files_path as fotos')
        ->join('programa as p','o.programa_id', '=', 'p.id')
        ->join('sector as s', 'o.sector_id', '=', 's.id')
        ->join('subprograma as sub', 'o.subprograma_id', '=', 'sub.id')
        ->join('funcion as f', 'o.funcion_id', '=', 'f.id')
        ->leftJoin('geo_obra as g', 'o.id', '=', 'g.obras_id')
        ->leftJoin('contrataciones_obra as c', 'o.id', '=', 'c.obra_id')
        ->leftJoin('fotos_obra as foto', 'o.id', '=', 'foto.obra_id')
        ->where('o.id', '=', $id);
        $results = $query->first();
        return $results;
    }

    public static function searchTotals(?bool $distrito = null, ?bool $provincia = null, ?bool $departamento = null, ?string $nivelGobierno = null)
    {
        $trueCount = ($distrito ? 1 : 0) + ($provincia ? 1 : 0) + ($departamento ? 1 : 0);
        if ($trueCount > 1) {
            return [];
        }

        $query = DB::table('obras as o')->join('geo_obra as g', 'o.id', '=', 'g.obras_id');

        if((!$departamento && !$provincia && !$distrito) || $departamento){
            $query = self::totalsDefaults($query, $nivelGobierno);
        }
        if($provincia){
            $query = self::totalsProvincia($query, $nivelGobierno);
        }
        if($distrito){
            $query = self::totalDistrito($query, $nivelGobierno);
        }

        return $query->get();
    }

    private static function totalsProvincia(Builder $query, ?string $nivelGobierno): Builder
    {
        $builder = $query->selectRaw('g.provincia, COUNT(o.id) as cantidad');
        $builder->groupBy('g.provincia');
        if($nivelGobierno){
            $builder->where('o.nivel_gobierno', '=', $nivelGobierno);
        }
        return $builder;
    }

    private static function totalDistrito(Builder $query, ?string $nivelGobierno): Builder
    {
        $builder = $query->selectRaw('g.distrito, COUNT(o.id) as cantidad');
        $builder->groupBy('g.distrito');
        if($nivelGobierno){
            $builder->where('o.nivel_gobierno', '=', $nivelGobierno);
        }
        return $builder;
    }

    private static function totalsDefaults(Builder $query, ?string $nivelGobierno)
    {
        $builder = $query->selectRaw('COUNT(o.id) as cantidad');
        if($nivelGobierno){
            $builder->where('o.nivel_gobierno', '=', $nivelGobierno);
        }
        return $builder;
    }
}