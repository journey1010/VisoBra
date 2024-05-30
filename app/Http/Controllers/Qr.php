<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Requests\Qr as QrRequest;
use Illuminate\Http\JsonResponse;
use App\Jobs\DeleteQr;
use App\Service\Qr as QrMaker;

class Qr extends Controller
{

    public function make(QrRequest $request): JsonResponse
    { 
        try{
            $path  = '';
            $logo = storage_path('app/public/logo.png');
            $qr = new QrMaker($request->cui, $logo);
            $qr->make();

            DeleteQr::dispatch($path)->delay(now()->addMinutes(1));
            return response()->json([
            ], 500);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Tenemos problemas, muchos problemas. Espere un momento.',
                $e->getMessage()
            ], 500);
        }
    }
}
