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
        if (!$request->hasHeader('Origin')) {
            return response()->json(['error' => 'Origin header is missing'], 400);
        }

        $parsedUrl = parse_url($request->header('origin'));

        if (!isset($parsedUrl['host'])) {
            return response()->json(['error' => 'Invalid Origin header'], 400);
        }

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
