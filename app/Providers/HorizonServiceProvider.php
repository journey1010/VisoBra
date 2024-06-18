<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Horizon\HorizonApplicationServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HorizonServiceProvider extends HorizonApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        parent::boot();

        // Horizon::routeSmsNotificationsTo('15556667777');
        // Horizon::routeMailNotificationsTo('example@example.com');
        // Horizon::routeSlackNotificationsTo('slack-webhook-url', '#channel');
    }

    /**
     * Register the Horizon gate.
     *
     * This gate determines who can access Horizon in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewHorizon', function (Request $request) {
            $allowedIps = [
                '45.5.58.105',
                '2803:9810:60a8:c810:d131:834c:4e6c:e3d0',
                '138.84.39.70',
            ];
        
            $ipAddress = trim($request->ip());
        
            return in_array($ipAddress, $allowedIps) ? Response::allow() : false;
        });
    }
}
