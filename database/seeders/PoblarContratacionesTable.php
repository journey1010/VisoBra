<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Obras;
use App\Jobs\ProcessContrataciones;

class PoblarContratacionesTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $registros = Obras::select('id, codigo_snip')
                    ->whereNotNull('codigo_snip')
                    ->get();
        foreach($registros as $registro){
            ProcessContrataciones::dispatch($registro->id, $registro->codigo_snip, 'store');
        }
    }
}
