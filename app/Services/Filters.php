<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Cache;

class Filters
{
    /**
     * Filter and retrieve total values based on the given parameters.
     *
     * @param bool|null $status Filter by status.
     * @param bool|null $funcion Filter by function.
     * @param bool|null $sector Filter by sector.
     * @return array Filtered totals based on the provided filters.
     */
    public function filterTotal(
        ?bool $status = null,
        ?bool $funcion = null,
        ?bool $sector = null, 
    ): array{
        $table = DB::table('obras as o');
        $collection  = [];
        if($status){
            $collection['estado_inversion'] = $this->forStatus($table);
        }
        if($funcion){
            $collection['funcion'] = $this->forFuncion($table);

        }  
        if($sector){
            $collection['sector'] = $this->forSector($table);
        }
        return $collection;
    }

    /**
     * Get the status count from the database, cached for 5 days.
     *
     * @param Builder $query The query builder instance.
     * @return mixed The cached status count data.
    */
    protected function forStatus(Builder $query): Collection
    {
        $value = Cache::remember('status_obras', 432000, function() use ($query){
            return $query->selectRaw('
                        COUNT(o.id) as cantidad,
                        o.estado_inversion as estadoInversion
                    ')
                    ->groupBy('o.estado_inversion')
                    ->get();
        });

        return $value; 
    }

    protected function forFuncion(Builder $query): Collection
    {
        $value = Cache::remember('funcion_obras', 432000, function() use ($query){
            return $query
                    ->leftJoin('funcion as f', 'o.funcion_id', '=', 'f.id')
                    ->selectRaw('
                        COUNT(o.id) as cantidad,
                        f.nombre as funcion
                    ')
                    ->groupBy('f.nombre')
                    ->get();
        });

        return $value;
    }

    public function forSector(Builder $query): Collection
    {
        return  Cache::remember('sector_obra', 432000, function() use ($query){
            return $query
            ->leftJoin('sector as s', 'o.sector_id', '=', 's.id')
            ->selectRaw('
                COUNT(o.id) as cantidad,
                s.nombre as funcion
            ')
            ->groupBy('s.nombre')
            ->get();
        });
    }
    
}