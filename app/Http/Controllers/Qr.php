<?php

namespace App\Http\Controllers;

use Exception;
use App\Http\Requests\Qr as QrRequest;
use Illuminate\Http\JsonResponse;
use App\Jobs\DeleteQr;
use App\Services\Qr as QrMaker;

class Qr extends Controller
{
    protected $visobraUrl = 'https://ofi5.mef.gob.pe/inviertews/Repseguim/ResumF12B?codigo=';

    public function make(QrRequest $request): JsonResponse
    {
        try {
            $data = $this->visobraUrl . $request->cui;
            $logo = storage_path('img/logo.png');
            $logoResizeToWidth = $request->size ?? 400; 
            $qr = new QrMaker($data, $logo, 600);
            

            $qr->configure([
                'size' => $request->size ?? 400,
                'margin' => $request->margin ?? 10,
                'backgroundColor' => [255,255,255],
                'logoResizeToWidth' => $logoResizeToWidth/2,
                'logoPunchoutBackground' => false,
                'roundBlockSizeMode' => 'Margin',
                'blockColor' => [205,49,51]
            ]);

            $path = $qr->make();
            $relativePath = str_replace(storage_path('app/public'), 'storage', $path);

            DeleteQr::dispatch($path)->delay(now()->addMinutes(1));

            return response()->json([
                'url' => $relativePath,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Tenemos problemas, muchos problemas. Espere un momento.', 

            ], 500);
        }
    }
}