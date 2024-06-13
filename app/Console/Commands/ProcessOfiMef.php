<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Obras;
use App\Jobs\ProcessObrasSSI;

class ProcessOfiMef extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-ofi-mef';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'processa ObrasSsiEndpoint';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $registros =  Obras::select('id','codigo_unico_inversion')
                ->where('estado_inversion', '=', 'ACTIVO')
                ->whereNotNull('codigo_unico_inversion')
                ->get();
        foreach($registros as $registro){
            ProcessObrasSSI::dispatch($registro->id, $registro->codigo_unico_inversion);
        }
    }
}
