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
use Illuminate\Support\Facades\Http;

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
    
            $geobra->store($response);
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
    
    private function configureHttpClient(HttpClient $http, GeobraEndpoint $geobra): void
    {
        $http->config(3, 100, 30, $geobra->headers);
    }
    
    private function fetchValidResponse(HttpClient $http, GeobraEndpoint $geobra): ?array
    {
        for ($i = 0; $i <= 1; $i++) {
            $response = $http->makeRequest(
                $geobra->setUrl($i),
                $geobra->method,
                $geobra->params
            );
    
            if ($geobra->validateFormat($response)) {
                return $response;
            }
        }
        return null;
    }
    
    private function handleException(\Exception $e): void
    {
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
