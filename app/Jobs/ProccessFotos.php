<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Exceptions\DataHandlerException;
use App\Services\FotoEndpoint;
use App\Services\HttpClient;
use App\Services\Mailer;
use App\Services\Notify;
use App\Services\Reporting;

class ProccessFotos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * ID de obra table obra
     */
    private $id;
    private $codigoInversion;
    private $method;
    private $data;

    /**
     * Create a new job instance.
     */
    public function __construct(?int $id = null, int $codigoInversion, array $data, string $method)
    {
        $this->id = $id;
        $this->codigoInversion = $codigoInversion;
        $this->method = strtolower($method) ;
        $this->data = $data ;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            $foto  = new FotoEndpoint(new HttpClient());
            $foto->configureHttpClient();
            $foto->changeParams(['id' => $this->codigoInversion]);
            $response = $foto->fetchValidateResponse();
            if($response === null){
                return;
            }
            $response['obra_id'] = $this->id;
            switch($this->method){
                case 'store':
                    $foto->store($response);                    
                    break;
                case 'update':
                    $foto->update($this->id, $response);
                    break;
                default:
                    throw new DataHandlerException('foto job: Metodo no encontrado . Linea 60 App\Job\Proccessfoto');
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
