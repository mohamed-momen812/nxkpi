<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    public function handle($request, Closure $next)
    {
        //return $next($request)
                //->header('Access-Control-Allow-Origin', '*')
        //        ->header('Access-Control-Allow-Methods', '*')
        //        ->header('Access-Control-Allow-Credentials', true)
        //        ->header('Access-Control-Allow-Headers', 'X-Requested-With,Content-Type,X-Token-Auth,Authorization')
        //        ->header('Accept', 'application/json');
         $response = $next($request);

         $response->headers->set('Access-Control-Allow-Origin', '*');
         $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
         $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');

         return $response;
    }
}
