<?php

namespace App\Jobs;

use App\Exceptions\DataHandlerException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


use App\Services\HttpClient;
use App\Services\ObrasEndpoint;
use App\Services\Reporting;
use App\Services\Notify;
use App\Services\Mailer;
use Exception;
use App\Exceptions\HttpClientException;

class ProcessUpdateObra implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $nombreInversion;
    private $id;

    public function __construct(int $id, string $nombreInversion)
    {
        $this->id = $id;
        $this->nombreInversion = $nombreInversion;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            $obras = new ObrasEndpoint(new HttpClient);
            $obras->configureHttpClient();
            $obras->changeParams([
                'cboNom' => 5,
                'txtNom' => $this->nombreInversion,
            ]);
            
            $response = $obras->fetchValidateResponse();
            if($response === null){
                throw new DataHandlerException('Update obra job: Error al obtener datos para la obra con id : ' . $this->id );
            }
            $obras->update($this->id, $response);
        } catch(HttpClientException $e){

        } catch(Exception $e){
            $notifier = new Notify(new Mailer());
            $notifier->clientNotify(
                to: 'soporteapps@regionloreto.gob.pe', 
                message: $e->getMessage(),
                subject: 'Fallo en visoobra al obtener datos');
            Reporting::loggin($e, 100);
        }
    }
}
