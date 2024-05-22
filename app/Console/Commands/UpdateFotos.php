<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Obras;
use App\Jobs\ProccessFotos;

class UpdateFotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-fotos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza las fotos de las obras';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $registros =  Obras::select('id','codigo_unico_inversion')
                ->where('estado_inversion', '=', 'ACTIVO')
                ->whereNotNull('codigo_unico_inversion')
                ->get();
        if(!$registros ){
            return;
        }

        foreach($registros as $registro){
            ProccessFotos::dispatch($registro->id, $registro->codigo_unico_inversion, 'update');
        }
    }
}