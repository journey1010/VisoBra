<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use App\Services\Contracts\HttpClientInterface;
use App\Exceptions\HttpClientException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Funcion;
use App\Models\Sector;
use App\Models\Subprograma;
use App\Models\Programa;
use App\Models\Obras;

class Prueba extends Controller
{
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

    public function test()
    {
        User::create([
            'name' => 'hola',
            'email' => 'hola@gmail',
            'password' => 'hola'
        ]);

    }

    public function testHttpObra(HttpClientInterface $http)
    {
        $data = [
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
        
        $headers = [];
        $url = "https://ofi5.mef.gob.pe/inviertePub/ConsultaPublica/traeListaProyectoConsultaAvanzada";
        $http->config(2,100, 30, $headers);
        $response = $http->makeRequest($url, 'post', $data);
        $data = $response['Data'][0];
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
        Obras::create(
            $this->dataStore
        );
        return $this->dataStore;   
    }

    public function httException()
    {
        try {
            throw new HttpClientException('Soy una excepcion');
        }catch (\Exception $e){
            $className = get_class($e);

            $executed = RateLimiter::attempt(
                $className,
                $perDay = 5,
                function() use ($e, $className){
                    Log::build([
                        'driver' => 'single',
                        'path' => storage_path('log/'.$className .'.log')
                    ])->critical($e->getMessage());
                }
            );
            
        }   
    }

}