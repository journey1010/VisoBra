<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\ProcessGeobra;

class ReloadGeobra extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reloadGeobra';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $registros = DB::table('obras as o')
                     ->leftJoin('geo_obra as g', 'o.id', '=', 'g.obras_id')
                     ->select(
                        'o.id as id',
                        'o.codigo_unico_inversion as codigo_unico_inversion'
                     )
                     ->whereNull('g.obras_id')
                     ->whereNotNull('o.codigo_unico_inversion')
                     ->get();
        foreach($registros as $registro){
            ProcessGeobra::dispatch($registro->id, $registro->codigo_unico_inversion)->delay(120);
        }
    }
}
