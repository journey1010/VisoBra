<?php

namespace App\Jobs;

use App\Exceptions\DataHandlerException;
use App\Services\HttpClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\ObrasEndpoint;
use App\Services\Contracts\DataHandler;

class ProcessObras implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    private $datos = [];
    private $id;
    private $method;

    public function __construct(?int $id = null, array $data, string $method)
    {
        $this->datos = $data;
        $this->id = $id;
        $this->method = strtolower($method);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $obras = new ObrasEndpoint(new HttpClient());
        $rows = count($this->datos);
        for( $i = 0; $i < $rows; $i++){
            switch($this->method){
                case 'store':
                    $obras->store($this->datos[$i]);
                    break;
                case 'update':
                    $obras->update($this->id, $this->datos[$i]);
                    break;
                default:
                    throw new DataHandlerException("Obras jobs: metodo no encontrado. Linea 50 App\Job\ProcessPoblarObras");
                    break;
            }

        }      
    }

    public function tags(): array
    {
        return ['Process_obra'];
    }
}
