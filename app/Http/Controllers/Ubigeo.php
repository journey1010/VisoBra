<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ubigeo\Ubigeo as UbigeoRequest;
use App\Services\Ubigeo as Ub;
use Exception;

class Ubigeo extends Controller
{
    public function departments(UbigeoRequest $request)
    {
        try{
            $result = Ub::departments($request->name, $request->id); 
            return response()->json([
                'items' => $result
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Ocurrio un problema al consultar los datos'
            ], 500);
        }
    }

    public function provinces(UbigeoRequest $request)
    {
        try{
            $result = Ub::provinces($request->name, $request->id); 
            return response()->json([
                'items' => $result
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Ocurrio un problema al consultar los datos'
            ], 500);
        }
    }

    public function districts(UbigeoRequest $request)
    {
        try{
            $result = Ub::districts($request->name, $request->id); 
            return response()->json([
                'items' => $result
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Ocurrio un problema al consultar los datos'
            ], 500);
        }
    }
}