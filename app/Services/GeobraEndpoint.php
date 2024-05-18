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

    public function setUrl(int $i): string
    {
        $this->url = 'https://ws.mineco.gob.pe/server/rest/services/cartografia_pip_georef_edicion_lectura/MapServer/'.$i.'/query';
        return $this->url;
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