<?php

namespace App\Jobs;

use App\Exceptions\DataHandlerException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\ObrasEndpoint;
use App\Services\Contracts\DataHandler;
use App\Services\HttpClient;
use App\Services\Mailer;
use App\Services\Notify;
use App\Services\Reporting;

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
        try{
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
        }catch(\Exception $e){
            $notifier = new Notify(new Mailer());
            $notifier->configLimiter(3, 'Geobra');
            $notifier->clientNotify(
                to: 'ginopalfo001608@gmail.com',
                message: $e->getMessage(),
                subject: 'Fallo en visoobra al obtener datos'
            );
            Reporting::loggin($e, 100);
        }
   
    }

    public function tags(): array
    {
        return ['Process_obra'];
    }
}
