<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use App\Services\HttpClient;

class Prueba extends Controller
{
    public function test()
    {
        User::create([
            'name' => 'hola',
            'email' => 'hola@gmail',
            'password' => 'hola'
        ]);

    }

    public function testHttpObra()
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
            "PageSize" => 10,
            "PageIndex" => 1,
            "sortField" => "MontoAlternativa",
            "sortOrder" => "desc",
            "chkFoniprel" => ""
        ];
        
        $headers = [];
        $url = "https://ofi5.mef.gob.pe/inviertePub/ConsultaPublica/traeListaProyectoConsultaAvanzada";
        $http = new HttpClient();
        $http->config(2,100, 30, $headers);
        $response = $http->makeRequest($url, 'post', $data);
        return $response;   
    }
}