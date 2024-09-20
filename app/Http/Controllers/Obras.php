<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchObras;
use App\Http\Requests\SearchTotals;
use App\Services\SpreedSheetHandler;
use App\Http\Requests\Obra\ById;
use Illuminate\Http\JsonResponse;
use App\Models\Obras as ObrasModel;
use App\Jobs\DeleteFile;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Obras extends Controller
{
    public function searchObras(SearchObras $request): JsonResponse
    {
        try{
            $results = ObrasModel::searchByFilters(
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

            if($results['total_items'] == 0){
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

    public function searchById(ById $request): JsonResponse
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
            ], 500);
        }
    }

    public function searchTotals(SearchTotals $request): JsonResponse
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

            /**
             * Retrive total of registers actives
            */
            $totals = Cache::remember('total_actives', 86400, function(){
                return DB::table('obras')->where('estado_inversion', '=', 'ACTIVO')->count();
            });
  
           return response()->json([ 
                'items' => $results,
                'totals' => $totals    
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Estamos experimentando problemas temporales.',
            ], 500);
        }
    }

    public function reportFile(SearchObras $request)
    {
        try{
            $results = ObrasModel::searchByFilters(
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
            );

            if($results->first() === null){
                return response()->json([
                    'message' => 'Sin resultados'
                ], 404);
            }

            $path = storage_path('app/public/' . 'report-' . date('Y-m-d-i-h-s') . '.xlsx');
            $spreed = new SpreedSheetHandler;
            $spreed->makeReport($results, $path);
            DeleteFile::dispatch($path)->delay(now()->addMinutes(1));
            return response()->download($path);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Estamos experimentando problemas temporales'
            ], 500);
        }
    }
}          