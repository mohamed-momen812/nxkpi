<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\OurInitializeTenancyByDomain;
use App\Http\Middleware\OurPreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    OurInitializeTenancyByDomain::class,
    OurPreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', function () {
        dd('kemo');
        return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
    });
});



Route::group(['prefix' => config('sanctum.prefix', 'sanctum')], static function () {
    Route::get('/csrf-cookie', [CsrfCookieController::class, 'show'])
        ->middleware([
            'web',
            OurInitializeTenancyByDomain::class // Use tenancy initialization middleware of your choice
        ])->name('sanctum.csrf-cookie');
});

Route::middleware([
        OurPreventAccessFromCentralDomains::class,
        OurInitializeTenancyByDomain::class,
        'api',
        'auth:sanctum'

    ])->prefix('/api')
    ->group(function () {
        Route::get('/', function () {
            return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
        });
        Route::get('/auth/user-profile', [\App\Http\Controllers\Api\AuthController::class, 'userProfile']);
        Route::post('/auth/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
        require __DIR__.'/centralAndTenant.php';
        Route::get('runCommands', function(){

            \Artisan::call('migrate');
            return 'done';
        });
    });


    Route::middleware([
        'api',
        OurInitializeTenancyByDomain::class,
        OurPreventAccessFromCentralDomains::class,
    ])->group(function () {
        require __DIR__.'/api.php';
        Route::post('api/auth/tenant/signin', [AuthController::class, 'login']);
    });
