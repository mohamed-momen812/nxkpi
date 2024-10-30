<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlanFeatureController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

Route::get('/lang/{lang}' , [\App\Http\Controllers\LangController::class , 'change']);

Route::get('plans', [PlanFeatureController::class ,'index']);
Route::get('plans/{id}', [PlanFeatureController::class ,'show']);

Route::group( ['middleware' => 'api','prefix' => 'auth' , InitializeTenancyByDomain::class,] , function ($router) {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/signin', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});




