<?php

namespace App\Services;

use App\Models\Geobra;
use App\Services\Contracts\DataHandler;
use App\Exceptions\DataHandlerException;
use Exception;

class GeobraEndpoint implements DataHandler
{
    /**
     * datos de consumo de endpoint
    */
    protected $url;

    public $params = [
        'f' => 'json',
        'where' => "UPPER(COD_UNICO) LIKE '%2192666%'",
        'returnGeometry' => true,
        'spatialRel' => 'esriSpatialRelIntersects',
        'maxAllowableOffset' => 0.01866138385297604,
        'outFields' => '*',
        'outSR' => 102100,
        'resultRecordCount' => 1,
    ];
    public $method = 'get';

    public $headers = [];

    /**Datos esperados desde el servicio */
    protected $dataHoped = [
        'DEPARTAMEN', 
        'PROVINCIA',
        'DISTRITO',
        'X',
        'Y',
    ];

    protected $http;

    public function __construct($http)
    {
        $this->http = $http;
    }

    public function configureHttpClient(?int $retry = 3, ?int $sleep=100, ?int $timeout = 30): void
    {
        $this->http->config($retry, $sleep, $timeout, $this->headers);
    }

    public function setUrl(int $i): string
    {
        $this->url = 'https://ws.mineco.gob.pe/server/rest/services/cartografia_pip_georef_edicion_lectura/MapServer/'.$i.'/query';
        return $this->url;
    }

    /**
     * 
     * Ejecuta una llamada al endpoint de geoinvierte. Comprueba la respuesta para validar el formato de datos esperado
     */
    public function fetchValidResponse(): ?array
    {
        for ($i = 0; $i <= 1; $i++) {
            $response = $this->http->makeRequest(
                $this->setUrl($i),
                $this->method,
                $this->params
            );
    
            if ($this->validateFormat($response)) {
                return $response;
            }
        }
        return null;
    }

    public function store(array $data)
    {  
        $clean  = $data['features'][0]['attributes'];
        Geobra::create([
            'obras_id' => $data['obras_id'],
            'provincia' => $clean['PROVINCIA'],
            'departamento' => $clean ['DEPARTAMEN'],
            'distrito' =>  $clean['DISTRITO'],
            'coordenadas' => [$clean['X'], $clean['Y']],
        ]);
    }

    public function validateFormat(array $data): bool
    {
        try{
            if(empty($data['features'])){
                return false;
            } 
            $search = $data['features'][0]['attributes'];
            foreach ($this->dataHoped as $key){
                if(!array_key_exists($key, $search)){
                    return false;
                }
            }
            return true;
        }catch(Exception $e){
            throw new DataHandlerException('Fallo en validacion de datos en geobraendpoint:' .$e->getMessage());
        }
    }
    
    public function update(int $id, array $data)
    {
        
    }
}