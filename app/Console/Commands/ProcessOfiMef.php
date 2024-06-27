<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Obras;
use App\Jobs\ProcessObrasSSI;
use App\Jobs\FixUbication;

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
        $registros = Obras::select('id', 'codigo_unico_inversion')
                            ->where(function($query) {
                                $query->where('estado_inversion', '=', 'ACTIVO')
                                    ->orWhereNull('estado_inversion');
                            })
                            ->whereNotNull('codigo_unico_inversion')
                            ->get();
        foreach($registros as $registro){
            ProcessObrasSSI::dispatch($registro->id, $registro->codigo_unico_inversion);
        }

        FixUbication::dispatch()->delay(3600);
    }
}
