<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AuthController;
use App\Http\Middleware\OurInitializeTenancyByDomain;
use App\Http\Middleware\OurPreventAccessFromCentralDomains;
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
});



