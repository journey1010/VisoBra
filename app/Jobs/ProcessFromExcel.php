<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Exceptions\DataHandlerException;
use Exception;
use App\Services\HttpClient;
use App\Services\ExcelEndpoint;
use App\Services\SpreedSheetHandler;
use App\Services\Reporting;
use App\Services\Notify;
use App\Services\Mailer;
use App\Models\Metadata;
use Throwable;

class ProcessFromExcel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $data, public string $method, public string $name)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try{
            $obras = new ExcelEndpoint(new HttpClient, new SpreedSheetHandler);
            $obras->changeParams($this->data);
            $obras->configureHttpClient(3, 100, 1000);
            $response = $obras->fetchValidateResponse();
            if(!$response){
                throw new DataHandlerException('Falla al obtener datos para obras, PoblarObrasFromExcelTable');
            }

            $toProcess = $obras->spreed->importSpreedSheet($obras->spreed->store($response));
            $data = $toProcess->toArray();
            $count = count($data);

            $method = strtolower($this->method);
            switch($method){
                case 'store';
                       if(!Metadata::where('endpoint_name', $this->name)->exists()){
                            Metadata::create([
                                'pages_size' => $count,
                                'total_rows' => $count,
                                'total_pages' => $count,
                                'endpoint_name' => $this->name
                            ]);
                       }
                       $obras->store($data);
                    break;
                case 'update':
                    $registros = $obras->isThereNewData($this->name, $count, $count, $count);
                    if (!$registros) {
                        return;
                    }
                    $obras->update($registros, $data);
                    break;
                default:
                    throw new Exception('Método no válido especificado.');
                    break;
            }
            $obras->spreed->drop();

        }catch(Throwable $e){
            Reporting::loggin($e, 100);
        }
    }

    public function tags(): array
    {
        return ['Process_obra_excel'];
    }
}