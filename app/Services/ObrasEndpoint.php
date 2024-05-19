<?php

namespace App\Services;

use App\Services\Contracts\DataHandler;
use App\Exceptions\DataHandlerException;
use App\Models\Obras;
use App\Models\Funcion;
use App\Models\Sector;
use App\Models\Subprograma;
use App\Models\Programa;
use App\Models\Metadata;

class ObrasEndpoint implements DataHandler
{
    /**
     * Attributes for endpoint obras 
    */

    public $url = 'https://ofi5.mef.gob.pe/inviertePub/ConsultaPublica/traeListaProyectoConsultaAvanzada';
    public $params = [
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
    public $method =  'post';
    public $headers =  []; 

    protected $http;
 
    protected $dataHoped = [
        'Funcion' => 'funcion_id',
        'Programa' => 'programa_id',
        'Subprograma' => 'subprograma_id',
        'Sector' => 'sector_id',
        'CodigoUnico' => 'codigo_unico_inversion',
        'Codigo' => 'codigo_snip',
        'Nombre' => 'nombre_inversion',
        'MontoAlternativa' => 'monto_viable',
        'Situacion' => 'situacion',
        'Estado' => 'estado_inversion',
        'Nivel' => 'nivel_gobierno',
        'Pliego' => 'entidad',
        'Opmi' => 'unidad_opmi',
        'ResponsableOpmi' => 'responsable_opmi',
        'Uei' => 'unidad_uei',
        'ResponsableUei' => 'responsable_uei',
        'Uf' => 'unidad_uf',
        'ResponsableUf' => 'responsable_uf',
        'Opi' => 'entidad_opi',
        'ResponsableOpi' => 'responsable_opi',
        'Ejecutora' => 'ejecutora',
        'FechaRegistro' => 'fecha_registro',
        'UltimoEstudio' => 'ultimo_estudio',
        'EstadoEstudio' => 'estado_estudio',
        'NivelViabilidad' => 'nivel_viabilidad',
        'ResponsableViabilidad' => 'responsable_viabilidad',
        'FechaViabilidad' => 'fecha_viabilidad',
        'Costo' => 'costo_actualizado',
        'Alternativa' => 'descripcion_alternativa',
        'Beneficiarios' => 'beneficiaros_habitantes',
        'DevActual'=>'devengado_año_vigente',
        'DevAcumuladoAnterior'=>'devengado_año_anterior',        
        'PimActual'=>'pim_año_vigente',
        'DevAcumulado'=>'devengado_acumulado',        
        'Marco'=>'marco',        
        'SaldoPorFinanciar'=>'saldo_por_financiar',
        'MesAnioPDev'=>'año_mes_primer_devengado',        
        'MesAnioUDev'=>'año_mes_ultimo_devengado',
        'IncluidoProgramacionPmi'=>'incluido_programacion_pmi',
        'IncluidoEjecucionPmi'=>'incluido_ejecucion_pmi',
        'GanadorFoniprel'=>'ganador_fronipel',
        'DescripcionCierre'=>'registro_cierre',
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
     * fetch a endpoint (throw a DataHandlerException if failed)
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
                if(!array_key_exists($key, $data['Data'][0])){
                    return false;
                }
            }
            return true;
        }catch(\Exception $e){
            throw new DataHandlerException('Fallo en validacion en obrasendpoint: ' . $e->getMessage());
        }
    }

    /**
     *@param Array $data Array asociativo de datosa guardar
     */

    public function store($data)
    {
        Obras::create($this->createRecords($data));
    }


    public function update(int $id, array $data)
    {
        Obras::where('id', $id)->update($this->dataStore);
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
                case 'Funcion':
                    $function = Funcion::firstOrCreate(['nombre' => $valueToStore]);
                    $this->dataStore[$value] = $function->id;
                    break;
                case 'Programa':
                    $programa = Programa::firstOrCreate(['nombre' => $valueToStore]);
                    $this->dataStore[$value] = $programa->id;
                    break;
                case 'Subprograma':
                    $subprograma = Subprograma::firstOrCreate(['nombre' => $valueToStore]); 
                    $this->dataStore[$value] = $subprograma->id;
                    break;
                case 'Sector':
                    $sector  = Sector::firstOrCreate(['nombre' => $valueToStore]);
                    $this->dataStore[$value] = $sector->id;
                    break;
            }
        }

        return $this->dataStore;
    }

    /**
     * @return bool is false if there isn´t new data in Consulta avanzada
     * @return int  number of new data
     */
    public function  isThereNewData(int $pageSize, int $totalRows, int $totalPage): bool|int
    {
        $metadata = Metadata::find(1); 
        if(!$metadata){
            throw new DataHandlerException('No hay registro de metadatos : table (metadata_list_obras)');
        }

        $diference  = $totalRows - $metadata->total_rows;
        
        if($diference != 0){
            return false;
        }
    
        Metadata::upMetada(1, $pageSize, $totalRows, $totalPage);
        return $diference;
    }
}