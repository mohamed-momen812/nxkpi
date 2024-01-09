<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

class OurPreventAccessFromCentralDomains extends PreventAccessFromCentralDomains
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

     public function handle(Request $request, Closure $next)
     {
        $parsedUrl = parse_url($request->header('origin'));
        
        $host = $parsedUrl['host'];

         if (in_array($host, config('tenancy.central_domains'))) {
             $abortRequest = static::$abortRequest ?? function () {
                 abort(404);
             };
 
             return $abortRequest($request, $next);
         }
 
         return $next($request);
     }
}
