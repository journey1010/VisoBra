<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;

class Ubigeo
{
    public static function departments(?string $name = null, ?int $id = null)
    {
        $query = DB::table('departments');
        if($name){
            return $query->where('name', 'like',  "%$name%")->first();
        }
        if($id){
            return $query->where('id', $id)->first();
        }

        return $query->get();
    }

    public static function provinces(?string $name = null, ?int $id = null)
    {
        $query = DB::table('provinces as p');
        if($name){
            $query->join('departments as d', 'p.department_id', '=', 'd.id')
                  ->select(
                    'p.id as id',
                    'p.name as name'
                  );
            return $query->where('d.name', 'like',  "%$name%")->get();
        }
        if($id){
            return $query->where('department_id', $id)->get();
        }

        return $query->get();
    }

    public static function districts(?string $name = null, ?int $id = null)
    {
        $query =  DB::table('districts as d');
        if($name){
            $query->join('provinces as p', 'd.province_id', '=', 'p.id')
                    ->select(
                    'd.id as id',
                    'd.name as name'
                    );
            return $query->where('p.name', 'like',  "%$name%")->get();
        }
        if($id){
            return $query->where('province_id', $id)->get();
        }

        return $query->get();
    }
}