<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\DB;

class FixUbication implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        DB::table('geo_obra')
            ->whereRaw('ST_X(coordenadas) > ST_Y(coordenadas)')
            ->update([
                'coordenadas' => DB::raw("ST_GeomFromText(CONCAT('POINT(', ST_Y(coordenadas), ' ', ST_X(coordenadas), ')'))")
            ]);
    }
}
