<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Obras;
use App\Jobs\ProcessGeobra;

class PoblarGeoObraTable extends Seeder
{
    /**
     * Run the database seeds.
    */

    public function run(): void
    {
        $registros = Obras::select('id', 'codigo_unico_inversion')
                    ->whereNotNull('codigo_unico_inversion')
                    ->get();
        foreach($registros as $registro){
            ProcessGeobra::dispatch($registro->id, $registro->codigo_unico_inversion);
        }
    }
}
