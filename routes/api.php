<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EntryController;

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

Route::group( ['middleware' => 'api','prefix' => 'auth'] , function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});
Route::group(['middleware' => 'auth:api'] , function (){
    Route::resource('categories' , \App\Http\Controllers\Api\CategoryController::class);
    Route::resource('kpis',\App\Http\Controllers\Api\KpiController::class);
    Route::resource('entries',EntryController::class);

    //new update route for entry update
    Route::put('entries' , [EntryController::class,'update']);

    //dashboard controller
    Route::get('kpi/total-ratio/{kpi}' , [\App\Http\Controllers\Api\DashboardController::class , 'totalRatio']);

    Route::apiResource('users' , \App\Http\Controllers\Api\UserController::class);

    Route::apiResource('dashboards' , \App\Http\Controllers\Api\DashboardController::class);
});

Route::get('test' , function (){
   return "hossam";
});

