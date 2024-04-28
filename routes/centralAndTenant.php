<?php


use App\Http\Controllers\Api\PlanFeatureController;
use Illuminate\Support\Facades\Route;

Route::resource('categories' , \App\Http\Controllers\Api\CategoryController::class);

// entries routes
Route::resource('entries',\App\Http\Controllers\Api\EntryController::class);
Route::put('entries' , [\App\Http\Controllers\Api\EntryController::class,'update']);
Route::get('entry/exportExcel', [\App\Http\Controllers\Api\EntryController::class, 'exportExcel']);
Route::get('entries/exportExcel/example', [\App\Http\Controllers\Api\EntryController::class, 'exportExcelExample']);
Route::post('entry/importExcel', [\App\Http\Controllers\Api\EntryController::class, 'importExcel']);

// kpis routes
Route::resource('kpis',\App\Http\Controllers\Api\KpiController::class);
//Route::post('kpis' , [\App\Http\Controllers\Api\KpiController::class , 'store'])->middleware('can::create-kpis');
Route::put('kpis/{id}' , [\App\Http\Controllers\Api\KpiController::class , 'update'])->middleware('can::edit-kpis');
Route::get('kpis/{id}' , [\App\Http\Controllers\Api\KpiController::class , 'show'])->middleware('can::view-kpis');
Route::put('kpis/enableOrDisable/{kpi}' , [\App\Http\Controllers\Api\KpiController::class , 'enableOrDisable']);
Route::post('kpi/enableOrDisableMany' , [\App\Http\Controllers\Api\KpiController::class , 'enableOrDisableMany']);
Route::get('kpis/exportExcel/example', [\App\Http\Controllers\Api\KpiController::class, 'exportExcelExample']);
Route::post('kpi/importExcel', [\App\Http\Controllers\Api\KpiController::class, 'importExcel']);
Route::delete('kpi/delete-many' , [\App\Http\Controllers\Api\KpiController::class , 'deleteMany']);

Route::get('kpi/total-ratio/{kpi}' , [\App\Http\Controllers\Api\KpiController::class , 'totalRatio']);

// users routes
Route::apiResource('users' , \App\Http\Controllers\Api\UserController::class);

// dashboard routes
Route::apiResource('dashboards' , \App\Http\Controllers\Api\DashboardController::class);
Route::get('dashboard/{dashboard}', [\App\Http\Controllers\Api\DashboardController::class, 'share'])->name('dashboard.share');


Route::apiResource('charts' , \App\Http\Controllers\Api\ChartController::class);

Route::apiResource('users' , \App\Http\Controllers\Api\UserController::class)->middleware('can:manage_users');;
Route::apiResource('groups' , \App\Http\Controllers\Api\GroupController::class);
Route::apiResource('companies' , \App\Http\Controllers\Api\CompanyController::class);
Route::get('frequencies' , [\App\Http\Controllers\Api\FrequencyController::class , 'index']);

Route::apiResource('roles' , \App\Http\Controllers\Api\RoleController::class);
Route::apiResource('permissions' , \App\Http\Controllers\Api\PermissionController::class);

Route::group(['prefix' => 'reports'] , function (){
    Route::get('top_perform' , [\App\Http\Controllers\Api\ReportController::class , 'topPerform']);
    Route::get('worst_perform' , [\App\Http\Controllers\Api\ReportController::class , 'worstPerform']);
    Route::get('multiple_kpis' , [\App\Http\Controllers\Api\ReportController::class , 'multipliKpis']);
    Route::get('kpi_performance', [\App\Http\Controllers\Api\ReportController::class, 'kpiPerformance']);
    Route::get('user_kpis', [\App\Http\Controllers\Api\ReportController::class, 'userKpis']);
});

