<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

class Test extends Controller
{
    public function  test()
    {
        try{
            $obras = new ExcelEndpoint(new HttpClient, new SpreedSheetHandler);
            $obras->configureHttpClient(3, 100, 1000);
            $response = $obras->fetchValidateResponse();
            if(!$response){
                throw new DataHandlerException('Falla al obtener datos para obras, PoblarObrasFromExcelTable');
            }

            $toProcess = $obras->spreed->importSpreedSheet($obras->spreed->store($response));
            $data = $toProcess->toArray();
            $count = count($data);

            $method = strtolower('update');
            switch($method){
                case 'store';
                       if(!Metadata::where('endpoint_name', 'endpoint_excel_2')->exists()){
                            Metadata::create([
                                'pages_size' => $count,
                                'total_rows' => $count,
                                'total_pages' => $count,
                                'endpoint_name' => 'endpoint_excel_2'
                            ]);
                       }
                       $obras->store($data);
                    break;
                case 'update':
                    $registros = $obras->isThereNewData('endpoint_excel_2', $count, $count, $count);
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
            $notifier = new Notify(new Mailer());
            $notifier->clientNotify(
                to: 'ginopalfo001608@gmail.com', 
                message: $e->getMessage(),
                subject: 'Fallo en visoobra al obtener datos');
            Reporting::loggin($e, 100);
        }
    }

    private function isExcelFile(?string $contentType): bool
    {
        return in_array($contentType, [
            'vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
