<?php

use App\Http\Controllers\LangController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlanFeatureController;
use Database\Seeders\DatabaseSeeder;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

// maping those routes to each central domain in the RouteServiceProvider in boot method

Route::get('/lang/{lang}' , action: [LangController::class , 'change']);

Route::get('plans', [PlanFeatureController::class ,'index']);
Route::get('plans/{id}', [PlanFeatureController::class ,'show']);

Route::group(['middleware' => 'api', 'prefix' => 'auth', InitializeTenancyByDomain::class,], function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/signin', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');
    Route::get('/user-profile', [AuthController::class, 'userProfile'])->middleware('auth:sanctum');
});