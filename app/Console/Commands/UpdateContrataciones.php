<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Obras;
use App\Jobs\ProcessContrataciones;

class UpdateContrataciones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-contrataciones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza las contrataciones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $registros = Obras::select('id', 'codigo_snip')
            ->whereNotNull('codigo_snip')
            ->get();
        foreach($registros as $registro){
            ProcessContrataciones::dispatch($registro->id, $registro->codigo_snip, 'update');
        }
    }
}
