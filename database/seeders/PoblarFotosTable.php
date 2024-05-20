<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Obras;
use App\Jobs\ProccessFotos;

class PoblarFotosTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registros = Obras::select('id', 'codigo_unico_inversion')
            ->whereNotNull('codigo_snip')
            ->get();
        foreach($registros as $registro){
            ProccessFotos::dispatch(null, $registro->id, $registro->codigo_unico_inversion, 'store');
        }
    }
}
