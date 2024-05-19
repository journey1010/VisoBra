<?php

namespace App\Jobs;

use App\Services\HttpClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\ObrasEndpoint;

class ProcessPoblarObras implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $datos = [];

    public function __construct(array $datos)
    {
        $this->datos = $datos;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $obras = new ObrasEndpoint(new HttpClient());
        $rows = count($this->datos);
        for( $i = 0; $i < $rows; $i++){
            $obras->store($this->datos[$i]);
        }      
    }

    public function tags(): array
    {
        return ['Poblar_obra'];
    }
}
