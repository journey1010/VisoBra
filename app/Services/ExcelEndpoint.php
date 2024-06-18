<?php

namespace App\Services;

use App\Services\Contracts\DataHandler;
use App\Services\SpreedSheetHandler;
use Illuminate\Support\LazyCollection;
use App\Models\Obras;
use App\Models\Metadata;

use App\Exceptions\DataHandlerException;
use Exception;

class ExcelEndpoint implements DataHandler
{
    protected $http;

    protected $url = 'https://ofi5.mef.gob.pe/inviertews/Ssi/expRepSSIDet';

    protected $method = 'POST';

    protected $headers = [];

    public $spreed;
    
    /**
     * Default params for get a list of "Obras" a level of department
     */
    protected $params = [
        "sect" => "",
        "plie" => 0,
        "dpto" => "16",
        "prov" => 0,
        "dist" => 0,
        "tipo" => "DPTO"
      ];
    
    public function __construct($http, SpreedSheetHandler $spreed)
    {
        $this->http = $http;
        $this->spreed = $spreed;
    }

    public function changeParams(array $data): void
    {
        foreach($data as $key => $value){
            $this->params[$key] = $value;
        }
    }

    public function configureHttpClient(?int $retry = 3, ?int $sleep=100, ?int $timeout = 100): void
    {
        $this->http->config($retry, $sleep, $timeout, $this->headers);
    }

    /**
     * fetch a endpoint (throw a DataHandlerException if failed)
     * @return ?string $response what is a array of data.
     * @return null is a $response has not validate format.
    */
    public function fetchValidateResponse(): ?string
    {
        $response = $this->http->makeRequest(
            $this->url,
            $this->method,
            $this->params,
            false
        );

        $contentType = [$this->http->response->header('Content-Type')];

        if ($this->validateFormat($contentType)){
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
            if(!in_array($data[0], [
                'vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])){
                return false;
            }
            return true;
        }catch(Exception $e){
            throw new DataHandlerException('Fallo en validacion de datos en Excel endpoint:' .$e->getMessage());
        }
        return true;
    }

    public function store(array $data)
    {
        $lazyCollection = LazyCollection::make($data);
        $pureData = $this->spreed->processData($lazyCollection);
        $pureData->each(function ($row) {
            if (!is_null($row['CUI']) && !is_null($row['NIVEL_GOBIERNO'])) {
                if (!Obras::where('codigo_unico_inversion', $row['CUI'])->exists()) {
                    Obras::create([
                        'codigo_unico_inversion' => $row['CUI'],
                        'nivel_gobierno' => $row['NIVEL_GOBIERNO']
                    ]);
                }
            }
        });
    }


    public function  isThereNewData(string $nameSearch, int $pageSize, int $totalRows, int $totalPage): bool|int
    {
        
        $metadata = Metadata::where('endpoint_name', '=', $nameSearch)->first(); 
        if(!$metadata){
            throw new DataHandlerException('No hay registro de metadatos : table (metadata_list_obras)');
        }

        $diference  = $totalRows - $metadata->total_rows;
        
        if($diference === 0){
            return false;
        }
    
        Metadata::upMetada($metadata->id, $pageSize, $totalRows, $totalPage, $nameSearch);
        return $diference;
    }

    public function update(int $take, array $data)
    {
        $lazyCollection = LazyCollection::make($data);
        $pureData = $this->spreed->processData($lazyCollection)->take($take);
        $pureData->each(function ($row) {
            if (!Obras::where('codigo_unico_inversion', $row['CUI'])->exists()) {
                Obras::create([
                    'codigo_unico_inversion' => $row['CUI'],
                    'nivel_gobierno' => $row['NIVEL_GOBIERNO']
                ]);
            }
        });
    }
}