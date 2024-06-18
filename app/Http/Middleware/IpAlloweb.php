<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpAlloweb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {            
        $allowedIps = [
            '45.5.58.105',
            '2803:9810:60a8:c810:d131:834c:4e6c:e3d0',
            '138.84.39.70'
            ];
        $ipAddress= $request->ip();
        if(in_array($ipAddress, $allowedIps)){
            return $next($request);
        }
        abort(403);
    }
}
