<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchObras;
use App\Http\Requests\SearchTotals;
use App\Http\Requests\Obra\ById;
use Illuminate\Http\JsonResponse;
use App\Models\Obras as ObrasModel;
use Exception;

class Obras extends Controller
{
    public function searchObras(SearchObras $request): JsonResponse
    {
        try{
            $results = ObrasModel::searchPaginate(
                estadoInversion: $request->estadoInversion,
                funcion: $request->funcion,
                subprograma: $request->subprograma,
                programa: $request->programa,
                sector: $request->sector,
                codeUnique: $request->codeUnique,
                snip: $request->snip,
                nombreObra: $request->nombreObra,
                provincia: $request->provincia,
                nivelGobierno: $request->nivelGobierno,
                distrito:$request->distrito,
                page: $request->page ?? 1,
                itemsPerPage: $request->itemsPerPage ?? 20
            );

            if(!$results){
                return response()->json([
                    'message' => 'No se encontraron resultados'
                ],404);
            }
            return response()->json($results, 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Estamos experimentando problemas temporales.',
                $e->getMessage()
            ], 500);
        }
    }

    public function searchById(ById $request)
    {
        try{  
            $results = ObrasModel::searchById($request->idObra, $request->onlyLocation);
            if(!$results){
                return response()->json([
                    'message' => 'No se encontraron resultados'
                ],404);
            }

            return response()->json($results, 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Estamos experimentando problemas temporales.',
                $e->getMessage()
            ], 500);
        }
    }

    public function searchTotals(SearchTotals $request)
    {
        try{  
            $trueCount = ($request->distrito ? 1 : 0) + ($request->provincia ? 1 : 0) + ($request->departamento ? 1 : 0);
            if ($trueCount > 1) {
                $results  = ObrasModel::totalsDefaults($request->nivelGobierno, $request->estado);
                return response()->json($results, 200);
            }

            if((!$request->departamento && !$request->provincia && !$request->distrito) || $request->departamento){
                $results  = ObrasModel::totalsDefaults($request->nivelGobierno, $request->estado);
                return response()->json($results, 200);
            }

            if($request->provincia){
                $results = ObrasModel::totalsProvincia($request->nivelGobierno, $request->estado);
            }
            if($request->distrito){
                $results = ObrasModel::totalDistrito($request->nivelGobierno, $request->estado);
            }

        
           return response()->json($results, 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Estamos experimentando problemas temporales.',
                $e->getMessage()
            ], 500);
        }
    }
}          