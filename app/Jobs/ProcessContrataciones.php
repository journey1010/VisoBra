<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Contrataciones;
use App\Services\HttpClient;
use App\Services\Mailer;
use App\Services\Notify;
use App\Services\Reporting;
use App\Services\ContratacionesEndpoint;

class ProcessContrataciones implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $codigoSnip;
    protected $method;

    /**
     * Create a new job instance.
     */
    public function __construct(int $id, int $codigoSnip, string $method)
    {
        $this->id = $id;
        $this->codigoSnip = $codigoSnip,
        $this->method = strtolower($method);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{

        }catch(\Exception $e){
            
        }
    }
}
