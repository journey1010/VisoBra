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
use App\Services\Reporting;

class ProcessGeobra implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
    */
    /**
     * ID de obra, table obras
     */
    protected $id;
    protected $codeUnique;

    public function __construct(int $id, int $codeUnique)
    {
        $this->id = $id;
        $this->codeUnique = $codeUnique;
    }

    /**
     * Execute the job.
    */

    public function handle(): void
    {
        try {
            $geobra = new GeobraEndpoint(new HttpClient());
     
            $geobra->configureHttpClient();
            $geobra->changeParams(['where' => $this->codeUnique]);
            $response = $geobra->fetchValidResponse();
        
            if ($response === null) {
                throw new DataHandlerException('Geobra Job: Fallo al obtener datos para el codigo de inversion con id :' . $this->codeUnique . '. Es posible que no existan registros en el portal de geoinvierte.');
            }
            $response['obras_id'] = $this->id;
            $geobra->store($response);
        } catch (\Exception $e) {
            Reporting::loggin($e, 100);
        }
    }

    public function tags(): array
    {
        return ['Process_geobra'];
    }
}
