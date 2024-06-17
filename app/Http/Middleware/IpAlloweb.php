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
            '45.5.58.105'
            ];
        $ipAddress= $request->ip();
        if(in_array($ipAddress, $allowedIps)){
            return $next($request);
        }  
        abort(403);
    }
}
