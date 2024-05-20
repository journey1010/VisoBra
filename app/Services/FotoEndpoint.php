<?php

namespace App\Services;
use App\Services\Contracts\DataHandler;
use App\Exceptions\DataHandlerException;
use Exception; 
use App\Models\Fotos;

class FotoEndpoint implements DataHandler
{
    public $url = 'https://ofi5.mef.gob.pe/inviertews/Dashboard/traeInformF12B_CU';

    /**Utilizar el id(codigo snip de la obra) para buscar sus contrataciones */
    public $params = [
        'id' => 1
    ];
    public $method = 'post';
    public $headers = [];
    public $http; 

    protected $dataHoped = [
        'RUTA_FOTO',
        'RUTA_FOTO_2',
        'RUTA_FOTO_3',
        'RUTA_FOTO_4'

    ];
    public function __construct($http)
    {
        $this->http = $http;
    }

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
            throw new DataHandlerException('Fallo en validacion de datos en Fotos endpoint:' .$e->getMessage());
        }
    }

    public function store(array $data)
    {
        $store = [];
        
        if(!array_key_exists('obra_id', $data)){
            throw new DataHandlerException('No se proporciono el id en Fotos endpoint para insertar registro'); 
        }
    
        foreach ($data as $index => $item) {
            if($index == 'obra_id'){
                continue;
            }
            $store[$index] = [
                'RUTA_FOTO' => $item['RUTA_FOTO'],
                'RUTA_FOTO_2' => $item['RUTA_FOTO_2'] ,
                'RUTA_FOTO_3' => $item['RUTA_FOTO_3'],
                'RUTA_FOTO_4' => $item['RUTA_FOTO_4']
            ];
        }
    
        Fotos::create([
            'obra_id' => $data['obra_id'],
            'files_path' => $store 
        ]);
    }

    public function update(int $id, array $data)
    {
        $store = [];
        foreach ($data as $index => $item) {
            if($index == 'obra_id'){
                continue;
            }
            $store[$index] = [
                'RUTA_FOTO' => $item['RUTA_FOTO'],
                'RUTA_FOTO_2' => $item['RUTA_FOTO_2'] ,
                'RUTA_FOTO_3' => $item['RUTA_FOTO_3'],
                'RUTA_FOTO_4' => $item['RUTA_FOTO_4']
            ];
        }

        $fotos = Fotos::find($id);
        $fotos->files_path = $store;
        $fotos->save();    
    }
}