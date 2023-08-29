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

Route::get('/lang/{lang}' , [\App\Http\Controllers\LangController::class , 'change'])->name('lang.change');

Route::group( ['middleware' => 'api','prefix' => 'auth'] , function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});
Route::group(['middleware' => 'auth:api'] , function (){
    // categories routes
    Route::resource('categories' , \App\Http\Controllers\Api\CategoryController::class);

    // entries routes
    Route::resource('entries',EntryController::class);
    Route::put('entries' , [EntryController::class,'update']);

    // kpis routes
    Route::resource('kpis',\App\Http\Controllers\Api\KpiController::class);
    Route::get('kpi/total-ratio/{kpi}' , [\App\Http\Controllers\Api\KpiController::class , 'totalRatio']);

    // users routes
    Route::apiResource('users' , \App\Http\Controllers\Api\UserController::class);

    // dashboard routes
    Route::apiResource('dashboards' , \App\Http\Controllers\Api\DashboardController::class);
    Route::apiResource('charts' , \App\Http\Controllers\Api\ChartController::class);

    Route::apiResource('users' , \App\Http\Controllers\Api\UserController::class);
    Route::apiResource('companies' , \App\Http\Controllers\Api\CompanyController::class);
    Route::get('frequencies' , [\App\Http\Controllers\Api\FrequencyController::class , 'inde    x']);
});

Route::get('test' , function (){
   return "hossam";
});

