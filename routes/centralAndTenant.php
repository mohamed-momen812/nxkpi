<?php


// categories routes
Route::resource('categories' , \App\Http\Controllers\Api\CategoryController::class);

// entries routes
Route::resource('entries',\App\Http\Controllers\Api\EntryController::class);
Route::put('entries' , [\App\Http\Controllers\Api\EntryController::class,'update']);

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
Route::get('frequencies' , [\App\Http\Controllers\Api\FrequencyController::class , 'index']);
