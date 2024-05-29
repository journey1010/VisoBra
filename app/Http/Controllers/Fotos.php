<?php

namespace App\Http\Controllers;

use App\Http\Requests\Fotos as FotosRequest;
use App\Models\Fotos as FotosModel;
use Exception;
use Illuminate\Http\JsonResponse;

class Fotos extends Controller
{
    public function colection(FotosRequest $request): JsonResponse
    {
        try{
            $year  = date('Y');

            $resultados = FotosModel::whereYear('created_at', $year)
                        ->whereNotNull('files_path')
                        ->inRandomOrder()
                        ->limit($request->items)
                        ->get()
                        ->pluck('files_path');
            $clean = $resultados->flatten()->take($request->items)->all();
            
            return response()->json($clean, 200);
        }catch(Exception $e){
            return response()->json();
        }
    }
}