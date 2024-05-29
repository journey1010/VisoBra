<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterObra;
use App\Services\Filters;
use Exception;

class FilterTotales extends Controller
{
    public function filterTotal(FilterObra $request)
    {
        try{
            $filters = new Filters();
            $respuesta = $filters->filterTotal(
                $request->estadoInversion,
                $request->funcion,
                $request->sector
            );
            return response()->json($respuesta, 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Estamos experimentando problemas temporales.'
            ], 500);
        }
    }
}