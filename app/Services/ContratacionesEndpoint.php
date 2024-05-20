<?php

namespace App\Services;

use App\Services\Contracts\DataHandler;
use App\Exceptions\DataHandlerException;
use Exception;
use App\Models\Contrataciones;

class ContratacionesEndpoint implements DataHandler
{
    public $url = 'https://ofi5.mef.gob.pe/inviertews/DashboardSeace/traeContrSeaceSSI';

    /**Utilizar para el id(codigo snip de la obra) para buscar sus contrataciones */
    public $params = [
        'id'  => 1,
        'vers' => 'v2'
    ];
    public $method = 'post';
    public $headers = [];
    public $http; 

    protected $dataHoped = [
        'FEC_CONVOCATORIA',
        'NUM_CONTRATO',
        'DES_PROCESO',
        'FEC_SUSCRIPCION',
        'NOM_CONTRATISTA',
        'URL_CONTRATO',
        'MTO_TOTAL'
    ];

    public function __construct($http)
    {
        $this->http = $http;
    }
    /**
     * Change params of endpoint to fetch
     */
    public function changeParams(array $data): void
    {
        foreach($data as $key => $value){
            $this->params[$key] = $value;
        }
    }

    public function configureHttpClient(?int $retry = 3, ?int $sleep=100, ?int $timeout = 30): void
    {
        $this->http->config($retry, $sleep, $timeout, $this->headers);
    }


    /**
     * fetch a endpoint (throw a DataHandlerException if failed)
     * @return array $response what is a array of data.
     * @return null is a $response has not validate format.
     */
    public function fetchValidateResponse(): ?array
    {
        $response = $this->http->makeRequest(
            $this->url,
            $this->method,
            $this->params
        );

        if ($this->validateFormat($response)) {
            return $response;
        }
        return null;
    }

    public function validateFormat(array $data): bool
    {
        try {
            if(empty($data)){
                return false;
            }
            $search = $data[0];
            foreach($this->dataHoped as $key){
                if(!array_key_exists($key, $search)){
                    return false;
                }
            }
            return true;
        }catch(Exception $e){
            throw new DataHandlerException('Fallo en validacion de datos en contrataciones endpoint:' .$e->getMessage());
        }
    }

    public function store(array $data)
    {
        $store = [];
        
        if(!array_key_exists('obra_id', $data)){
            throw new DataHandlerException('No se proporciono el id en contrataciones endpoint para insertar registro'); 
        }
    
        foreach ($data as $index => $item) {
            if($index == 'obra_id'){
                continue;
            }
            $store[$index] = [
                'FEC_CONVOCATORIA' => $item['FEC_CONVOCATORIA'],
                'NUM_CONTRATO' => $item['NUM_CONTRATO'],
                'DES_PROCESO' => $item['DES_PROCESO'],
                'FEC_SUSCRIPCION' => $item['FEC_SUSCRIPCION'],
                'NOM_CONTRATISTA' => $item['NOM_CONTRATISTA'],
                'URL_CONTRATO' => $item['URL_CONTRATO'],
                'MTO_TOTAL' => $item['MTO_TOTAL'],
            ];
        }
    
        Contrataciones::create([
            'obra_id' => $data['obra_id'],
            'contrataciones' => $store 
        ]);
    }

    public function update(int $id, array $data)
    {
        
    }

}
