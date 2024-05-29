<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchObras;
use App\Http\Requests\SearchTotals;
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
                
            ], 500);
        }
    }

    public function searchById(Request $request)
    {
        try{  
            $idObra = (int) $request->idObra;
            if(!is_int($idObra)){
                return response()->json([
                    'message' => 'Formato de ID para obra debe ser numérico'
                ], 422);
            }
            $results = ObrasModel::searchById($idObra);
            if(!$results){
                return response()->json([
                    'message' => 'No se encontraron resultados'
                ],404);
            }

            return response()->json($results, 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Estamos experimentando problemas temporales.'
            ], 500);
        }
    }

    public function searchTotals(SearchTotals $request)
    {
        try{  
            $results = ObrasModel::searchTotals(
                $request->distrito,
                $request->provincia,
                $request->departamento,
                $request->nivelGobierno
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
            ], 500);
        }
    }
}          