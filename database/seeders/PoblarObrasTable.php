<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Exceptions\DataHandlerException;
use Exception;
use App\Jobs\ProcessPoblarObras;
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
    
    protected $pageSize;
    protected $retry = 3;
    

    public function run(): void
    {
        try{
            $http = new HttpClient();
            $http->config($this->retry, 200, 30, []);
            $response = $http->makeRequest( $this->url, 'post', $this->params);

            $obras = new ObrasEndpoint();
            if(!$obras->validateFormat($response)){
                throw new DataHandlerException('Datos incompatibles, el formato de datos esperados no es el correcto. Al buscar en los datos de Consulta avanzada.');
            }
            
            Metadata::create([
                 'pages_size' => $response['PageSize'],
                 'total_rows' => $response['TotalRows'],
                 'total_pages' => $response['TotalPage'],
            ]);

            $this->params['PageSize'] = 100;
            for ($i = 1 ; $i <= 122; $i++){
                $this->params['PageIndex'] = $i;
                $response = $http->makeRequest($this->url, 'post', $this->params);
                ProcessPoblarObras::dispatch($response['Data'], $obras);
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