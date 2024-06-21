<?php

namespace App\Services;

use App\Services\Contracts\DataHandler;
use App\Exceptions\DataHandlerException;
use App\Models\Obras;
use App\Models\Funcion;
use App\Models\Sector;
use App\Models\Subprograma;
use App\Models\Programa;

class ObrasSsiEndpoint implements DataHandler
{
    /**
     * Attributes for endpoint obras 
    */

    public $url = 'https://ofi5.mef.gob.pe/inviertews/Dashboard/traeDetInvSSI';
    public $params = [
        'id' => 0,
        'tipo' => 'SIAF'
    ];
    public $method =  'post';
    public $headers =  []; 

    protected $http;
 
    protected $dataHoped = [
        'FUNCION' => 'funcion_id',
        'DES_PROGRAMA' => 'programa_id',
        'DES_SUB_PROGRAMA' => 'subprograma_id',
        'SECTOR' => 'sector_id',
        'CODIGO_UNICO' => 'codigo_unico_inversion',
        'COD_SNIP' => 'codigo_snip',
        'NOMBRE_INVERSION' => 'nombre_inversion',
        'MTO_VIABLE' => 'monto_viable',
        'SITUACION' => 'situacion',
        'ESTADO' => 'estado_inversion',
        'NIVEL' => 'nivel_gobierno',
        'ENTIDAD' => 'entidad',
        'DES_UNIDAD_UF' => 'unidad_uf',
        'FEC_REGISTRO' => 'fecha_registro',
        'FEC_VIABLE' => 'fecha_viabilidad',
        'COSTO_ACTUALIZADO' => 'costo_actualizado',
        'BENEFICIARIO' => 'beneficiaros_habitantes',
        'DEV_ANO_VIGENTE'=>'devengado_año_vigente',
        'DEV_ACUM_ANT'=>'devengado_año_anterior',        
        'PIM_ANO_VIGENTE'=>'pim_año_vigente',
        'DEV_ACUMULADO'=>'devengado_acumulado',        
        'MARCO'=>'marco',        
        'MES_ANO_PRI_DEV'=>'año_mes_primer_devengado',        
        'MES_ANO_ULT_DEV'=>'año_mes_ultimo_devengado',
        'IND_REG_FONIPREL'=>'ganador_fronipel',
        'CIERRE_REGISTRADO'=>'registro_cierre',
        'DES_UNIDAD_UEI'=> 'unidad_uei',
        'DES_UNIDAD_UF' => 'unidad_uf',
        'NOMBRE_OPMI' => 'unidad_opmi',
        'ENTIDAD' => 'entidad'
    ];

    protected $dataStore = [];

    public function __construct($http)
    {
        $this->http = $http;
    }

    public function configureHttpClient(?int $retry = 3, ?int $sleep=100, ?int $timeout = 30): void
    {
        $this->http->config($retry, $sleep, $timeout, $this->headers);
    }

    /**
     * Hope asociative array
     */
    public function changeParams(array $params) :void
    {   
        foreach($params as $key  =>  $value){
            $this->params[$key] = $value;
        }
    }

    /**
     * fetch a endpoint or throw a DataHandlerException if failed
     * @return array $response what is a array of data.
     * @return null is a $response has not validate format.
     * 
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
        try{
            if(empty($data)){
                return false;
            }
        
            foreach ($this->dataHoped as $key => $value){
                if(!array_key_exists($key, $data[0])){
                    return false;
                }
            }
            return true;
        }catch(\Exception $e){
            throw new DataHandlerException('Fallo en validacion en ObrasSsiEndpoint: ' . $e->getMessage());
        }
    }

    /**
     *@param Array $data Array asociativo de datosa guardar
     */

    public function store($pureData)
    {

    }


    public function update(int $id, array $data)
    {   
        $toUpdate = $this->createRecords($data[0]);
        $toUpdate['updated_at'] = date('Y-m-d H:i:s');
        $obras = Obras::find($id);
        $obras->fill($toUpdate);
        $obras->save();
    }

    /**
     * Crea un array asociativo basandose en el nombre de las columnas
     */
    private function createRecords(array $data): array
    {
        foreach($this->dataHoped as $key => $value){
            $valueToStore = $data[$key]; 
            $this->dataStore = array_merge($this->dataStore, [$value =>$valueToStore]);
            switch($key){
                case 'FUNCION':
                    $function = Funcion::firstOrCreate(['nombre' => $valueToStore]);
                    $this->dataStore[$value] = $function->id;
                    break;
                case 'DES_PROGRAMA':
                    $programa = Programa::firstOrCreate(['nombre' => $valueToStore]);
                    $this->dataStore[$value] = $programa->id;
                    break;
                case 'DES_SUB_PROGRAMA':
                    $subprograma = Subprograma::firstOrCreate(['nombre' => $valueToStore]); 
                    $this->dataStore[$value] = $subprograma->id;
                    break;
                case 'SECTOR':
                    $sector  = Sector::firstOrCreate(['nombre' => $valueToStore]);
                    $this->dataStore[$value] = $sector->id;
                    break;
            }
        }

        return $this->dataStore;
    }
}