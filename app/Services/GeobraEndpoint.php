<?php

namespace App\Services;

use App\Models\Geobra;
use App\Services\Contracts\DataHandler;

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
        $clean  = $data['features']['attributes'];
        Geobra::create([
            'obras_id' => $data['obras_id'],
            'provincia' => $clean['PROVINCIA'],
            'departamento' => $clean ['DEPARTAMEN'],
            'distrito' =>  $clean['DISTRITO'],
            'coordenada' => [$clean['X'], $clean['Y']],
        ]);
    }

    public function validateFormat(array $data): bool
    {
        if(!is_array($data) || empty($data)){
            return false;
        } 

        foreach ($this->dataHoped as $key => $value){
            if(!array_key_exists($key, $data['features']['attributes'])){
                return false;
            }
        }

        return true;

    }
    
    public function update(int $id, array $data)
    {
        
    }
}