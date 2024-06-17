<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\IpAlloweb;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__.'/../routes/console.php',
        using: function () {
            Route::prefix('api')
                ->middleware('throttle:api')
                ->group(base_path('routes/api.php'));
     
            Route::middleware('web')
                ->middleware('throttle:web')
                ->group(base_path('routes/web.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'ipAllowed' => IpAlloweb::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
