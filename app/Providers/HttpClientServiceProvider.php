<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\HttpClient;
USE App\Services\Contracts\HttpClientInterface;

class HttpClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(HttpClientInterface::class, function($app){
            return new HttpClient();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
