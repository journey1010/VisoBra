<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
// use App\Models\Obras as ObrasModel;
use Exception;

class Obras extends Controller
{
    public function mainObras(): JsonResponse
    {
        try{
             //$obras = ObrasModel::
        }catch(Exception $e){
            return response()->json([
                'message' => 'Estamos experimentando problemas temporales.'
            ], 500);
        }
    }
}