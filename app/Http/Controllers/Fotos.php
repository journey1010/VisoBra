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
            $resultados = FotosModel::whereYear('created_at')
                        ->whereNotNull('files_path')
                        ->inRandomOrder()
                        ->limit($request->items)
                        ->get();
            $pureData = json_encode($resultados, true);
            
            return response()->json($resultados, 200);
        }catch(Exception $e){
            return response()->json();
        }
    }
}