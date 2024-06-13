<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Exceptions\DataHandlerException;
use Exception;
use App\Jobs\ProcessInsertObras;
use App\Services\HttpClient;
use App\Services\ObrasEndpoint;
use App\Services\Reporting;
use App\Services\Notify;
use App\Services\Mailer;
use App\Models\Metadata;


class PoblarObrasTable extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        try{
            $obras = new ObrasEndpoint(new HttpClient());
            $obras->configureHttpClient();

            $response = $obras->fetchValidateResponse();
            if($response === null){
                throw new DataHandlerException('Fallo al obtener datos para poblar obras, Seeder PoblarObrasTable');
            }
            
            Metadata::create([
                 'pages_size' => $response['PageSize'],
                 'total_rows' => $response['TotalRows'],
                 'total_pages' => $response['TotalPage'],
                 'endpoint_name' => 'obras_endpoint'
            ]);

            $obras->changeParams(['PageSize' => 100]);
            for ($i = 1 ; $i <= 122; $i++){
                $obras->changeParams(['PageIndex' => $i]);
                $response = $obras->fetchValidateResponse();
                ProcessInsertObras::dispatch($response);
            }
        }catch(Exception $e){
            $notifier = new Notify(new Mailer());
            $notifier->clientNotify(
                to: 'ginopalfo001608@gmail.com', 
                message: $e->getMessage(),
                subject: 'Fallo en visoobra al obtener datos');
            Reporting::loggin($e, 100);
        }
    }
}