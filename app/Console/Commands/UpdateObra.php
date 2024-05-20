<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Obras;
use App\Jobs\ProcessUpdateObra;


class UpdateObra extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-obra';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Busca los registros para las obras con estado de inversion activo y las actualiza';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $registros =  Obras::select('id','nombre_inversion')
                    ->where('estado_inversion', '=', 'ACTIVO')
                    ->whereNotNull('nombre_inversion')
                    ->get();
        if(!$registros ){
            return;
        }
        
        foreach($registros as $registro){
            ProcessUpdateObra::dispatch($registro->id, $registro->nombre_inversion);
        }
    }
}
