<?php

namespace App\Http\Middleware;

use Closure;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

class OurInitializeTenancyByDomain extends InitializeTenancyByDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if (!$request->hasHeader('Origin')) {
            return response()->json(['error' => 'Origin header is missing'], 400);
        }

        $parsedUrl = parse_url($request->header('origin'));

        if (!isset($parsedUrl['host'])) {
            return response()->json(['error' => 'Invalid Origin header'], 400);
        }

        $host = $parsedUrl['host'];
        return $this->initializeTenancy(
            $request, $next, $host
        );
    }
}
