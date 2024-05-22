<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

class Reporting
{
    /**
     * Guarda en los archivos de los Un error
     * @param $attemps => limite de veces que puede ser reportado un error
     * @param $e => Error lanzado
     * @return void
     */
    public static function loggin(Exception $e, ?int $attemps = 100): void
    {   
        $className = get_class($e);
        RateLimiter::attempt(
            $className, 
            $perDay = $attemps,
            function() use ($e, $className){
                Log::build([
                    'driver' => 'single',
                    'path' => storage_path("logs/$className.log")
                ])->critical($e->getMessage());
            }  
        );
    }
}
