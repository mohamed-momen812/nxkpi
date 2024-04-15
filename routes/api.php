<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EntryController;
use App\Http\Controllers\Api\PlanFeatureController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/lang/{lang}' , [\App\Http\Controllers\LangController::class , 'change']);

Route::group( ['middleware' => 'api','prefix' => 'auth' , \Stancl\Tenancy\Middleware\InitializeTenancyByDomain::class,] , function ($router) {
    Route::post('/signin', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::get('test' , function (){
   return "hossam";
});

Route::get('plans', [PlanFeatureController::class ,'index']);
Route::get('plans/{id}', [PlanFeatureController::class ,'show']);

Route::group( ['middleware'=> 'auth:sanctum','prefix'=> 'plans'], function ($router) {
    Route::post('subscribe', [PlanFeatureController::class ,'subscribeToPlan']);
    Route::post('cancel-subscription', [PlanFeatureController::class ,'cancelSubscription']);
});
