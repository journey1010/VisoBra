<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\HttpClient;
use App\Services\ObrasSsiEndpoint;
use App\Services\Reporting;
use App\Services\Notify;
use App\Services\Mailer;
use Exception;
use App\Exceptions\DataHandlerException;


class ProcessObrasSSI implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $cui;
    private $id;

    public function __construct(int $id, int $cui)
    {
        $this->cui = $cui;
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            $obras = new ObrasSsiEndpoint(new HttpClient);
            $obras->configureHttpClient();
            $obras->changeParams([
                'id' => $this->cui,
            ]);
            
            $response = $obras->fetchValidateResponse();
            if($response === null){
                throw new DataHandlerException('Update obra job: Error al obtener datos para la obra con id : ' . $this->cui );
            }
            $obras->update($this->id, $response);
        } catch(Exception $e){
            $notifier = new Notify(new Mailer());
            $notifier->clientNotify(
                to: 'ginopalfo001608@gmail.com', 
                message: $e->getMessage(),
                subject: 'Fallo en visoobra al obtener datos');
            Reporting::loggin($e, 100);
        }
    }
}
