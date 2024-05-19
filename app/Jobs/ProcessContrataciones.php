<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\HttpClient;
use App\Services\Mailer;
use App\Services\Notify;
use App\Services\Reporting;
use App\Services\ContratacionesEndpoint;
use App\Exceptions\DataHandlerException;

class ProcessContrataciones implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Foreign key of obra
     */
    protected $id;
    protected $codigoSnip;
    protected $method;

    /**
     * Create a new job instance.
     */
    public function __construct(int $id, int $codigoSnip, string $method)
    {
        $this->id = $id;
        $this->codigoSnip = $codigoSnip;
        $this->method = strtolower($method);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            $contrataciones  = new ContratacionesEndpoint(new HttpClient());
            $contrataciones->configureHttpClient();
            $contrataciones->changeParams(['id' => $this->codigoSnip]);
            $response = $contrataciones->fetchValidateResponse();
            if($response === null){
                throw new DataHandlerException('Contrataciones Endpoint : Fallo al obtener datos para el codigo snip con id :' . $this->id);
            }
            $response['obra_id'] = $this->id;
            switch($this->method){
                case 'store':
                    $contrataciones->store($response);                    
                    break;
                case 'update':
                    $contrataciones->update($this->id, $response);
                    break;
                default:
                    throw new DataHandlerException('Contrataciones job: Metodo no encontrado . Linea 60 App\Job\ProccessContrataciones');
                    break;
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
}
