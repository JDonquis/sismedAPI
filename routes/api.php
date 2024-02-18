<?php

use App\Http\Controllers\CancellationController;
use App\Http\Controllers\ConfigurationProductController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\HierarchyController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OutputController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login',[UserController::class,'login']);
Route::get('fail-login',[UserController::class,'failLogin'])->name('login');

Route::group(['prefix' => 'dashboard','namespace' => 'App\Http\Controllers', 'middleware' => ['auth:sanctum','ability:origin,branch']], function() {
    

    Route::apiResource('entities',HierarchyController::class);
    Route::apiResource('users',UserController::class);
    Route::apiResource('products',ProductController::class);
    Route::apiResource('entries',EntryController::class);
    Route::apiResource('outputs',OutputController::class);
    Route::apiResource('inventories',InventoryController::class);
    Route::apiResource('organizations',OrganizationController::class);


    Route::get('config-products', [ConfigurationProductController::class,'index']);
    Route::post('config-products/{type}', [ConfigurationProductController::class,'store']);
    Route::put('config-products/{type}/{id}', [ConfigurationProductController::class,'update']);
    Route::delete('config-products/{type}/{id}', [ConfigurationProductController::class,'destroy']);

    Route::post('cancellation/{type}',[CancellationController::class,'index']);
    Route::get('outputs/generate-order/{guide}',[OutputController::class,'generateOutputOrder']);


});