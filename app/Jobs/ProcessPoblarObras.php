<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\ObrasEndpoint;
use App\Models\Obras;

class ProcessPoblarObras implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $datos = [];
    private $obrasHandler;

    public function __construct(array $datos, ObrasEndpoint $obrasHandler)
    {
        $this->datos = $datos;
        $this->obrasHandler = $obrasHandler;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $rows = count($this->datos);
        for( $i = 0; $i <= $rows; $i++){
            $this->obrasHandler->store($this->datos[$i]);
        }      
    }
}
