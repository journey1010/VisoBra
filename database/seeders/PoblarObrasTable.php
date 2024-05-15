<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Exceptions\DataHandlerException;
use App\Exceptions\HttpClientException;
use Exception;
use App\Jobs\ProcessPoblarObras;
use App\Services\HttpClient;
use App\Services\ObrasEndpoint;
use App\Services\Reporting;
use App\Services\Notify;
use App\Models\Metadata;


class PoblarObrasTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    protected $url = 'https://ofi5.mef.gob.pe/inviertePub/ConsultaPublica/traeListaProyectoConsultaAvanzada';
    protected $params = [
        "filters" => "",
        "ip" => "",
        "cboNom" => "1",
        "txtNom" => "",
        "cboDpto" => "16",
        "cboProv" => "0",
        "cboDist" => "0",
        "optUf" => "*",
        "cboGNSect" => "*",
        "cboGNPlie" => "",
        "cboGNUF" => "",
        "cboGR" => "*",
        "cboGRUf" => "",
        "optGL" => "*",
        "cboGLDpto" => "*",
        "cboGLProv" => "*",
        "cboGLDist" => "*",
        "cboGLUf" => "",
        "cboGLManPlie" => "*",
        "cboGLManUf" => "",
        "cboSitu" => "*",
        "cboNivReqViab" => "*",
        "cboEstu" => "*",
        "cboEsta" => "*",
        "optFecha" => "*",
        "txtIni" => "",
        "txtFin" => "",
        "chkMonto" => false,
        "txtMin" => "",
        "txtMax" => "",
        "tipo" => "1",
        "cboFunc" => "0",
        "chkInactivo" => "0",
        "cboDivision" => "0",
        "cboGrupo" => "0",
        "rbtnCadena" => "T",
        "isSearch" => false,
        "PageSize" => 1,
        "PageIndex" => 1,
        "sortField" => "MontoAlternativa",
        "sortOrder" => "desc",
        "chkFoniprel" => ""
    ];
    protected $pageSize;
    protected $retry = 3;
    

    public function run(): void
    {
        try{
            $http = new HttpClient();
            $http->config($this->retry, 200, 30, []);
            $response = $http->makeRequest( $this->url, 'post', $this->params);
            $data = $response['Data'][0];

            $obras = new ObrasEndpoint();
            if(!$obras->validateFormat($data)){
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
                ProcessPoblarObras::dispatch($response['Data'], $http);
            }

        }catch(HttpClientException $e){
            Reporting::loggin($e, 100);
        }catch(DataHandlerException $e){
            Reporting::loggin($e, 100);
        }catch(Exception $e){
            Reporting::loggin($e, 100);
        }
    }
}
