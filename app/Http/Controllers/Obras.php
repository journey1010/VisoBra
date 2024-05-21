<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchObras;
use Illuminate\Http\JsonResponse;
use App\Models\Obras as ObrasModel;
use Exception;

class Obras extends Controller
{
    public function searchObras(SearchObras $request): JsonResponse
    {
        try{
            $results = ObrasModel::searchPaginate(
                $request->input('estadoInversion'),
                $request->input('funcion'),
                $request->input('subprograma'),
                $request->input('programa'),
                $request->input('sector'),
                $request->input('codeUnique'),
                $request->input('snip'),
                $request->input('nombreObra'),
                $request->input('provincia'),
                $request->input('nivelGobierno'),
                $request->input('distrito'),
                $request->input('page', 1),
                $request->input('itemsPerPage', 20)
            );

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

    public function searchTotals(Request $request)
    {
        try{  
            $idObra =$request->all();
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
}          