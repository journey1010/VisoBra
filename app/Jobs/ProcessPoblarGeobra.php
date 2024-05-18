<?php

namespace App\Jobs;

use App\Exceptions\DataHandlerException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Services\HttpClient;
use App\Services\GeobraEndpoint;
use App\Services\Notify;
use App\Services\Reporting;
use App\Services\Mailer;

class ProcessPoblarGeobra implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
    */
    protected $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
    */

    public function handle(): void
    {
        try {
            $geobra = new GeobraEndpoint();
            $http = new HttpClient();
            $this->configureHttpClient($http, $geobra);
    
            $response = $this->fetchValidResponse($http, $geobra);
        
            if ($response === null) {
                throw new DataHandlerException('Fallo al obtener datos para el codigo de inversion con id :' . $this->id);
            }
            $response['obras_id'] = $this->id;
            $geobra->store($response);
        } catch (\Exception $e) {
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
