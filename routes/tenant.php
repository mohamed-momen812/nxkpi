<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\OurInitializeTenancyByDomain;
use App\Http\Middleware\OurPreventAccessFromCentralDomains;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::middleware([
    OurInitializeTenancyByDomain::class,
    OurPreventAccessFromCentralDomains::class,
    'api',
    'auth:sanctum',
])->prefix('/api')->group(function () {
    Route::post('/auth/tenant/signin', [AuthController::class, 'login'])->withoutMiddleware('auth:sanctum');
    Route::post('/auth/tenant/logout', [AuthController::class, 'logout']);
    Route::post('/auth/tenant/refresh', [AuthController::class, 'refresh']);
    Route::post('/auth/tenant/change-password', [AuthController::class, 'changePassword']);
    Route::get('/auth/tenant/user-profile', [AuthController::class, 'userProfile']);
    require __DIR__.'/centralAndTenant.php';


    Route::get('runCommands', function(){

        Artisan::call('tenants:seed', [
            '--tenants' => $tenant['id'],
            '--class'   => PlanFeatureSeeder::class,
        ]);
    
        // Artisan::call('db:seed', ['--class' => DatabaseSeeder::class]);
        // $result = \Artisan::call('migrate', ['--path' => 'database/migrations/tenant/2024_09_10_120016_create_group_user_table.php']);
        // $result = Artisan::call('migrate', ['--path' => 'database/migrations/tenant']); 
        return 'done1';
    });
});



