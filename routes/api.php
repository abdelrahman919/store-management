<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::prefix('client')->group(function () {
    
//     Route::apiResource('/', ClientController::class);
//     Route::post('{client}/allow-credit', ClientController::class, 'ClientController@allowCredit');
// });


Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
});

Route::middleware('auth:sanctum')->prefix('client')->controller(ClientController::class)->group(function () {
    Route::apiResource('/', ClientController::class);
    Route::post('{client}/allow-credit', 'allowCredit');
    Route::post('{client}/credit_limit', 'updateCreditLimit');
});


Route::controller(SupplierController::class)->group(function () {
    Route::apiResource('supplier', SupplierController::class);
    
});


Route::prefix('item')->controller(ItemController::class)->group(function () {
   Route::apiResource('/', ItemController::class);
   Route::delete('code/{code}', 'destroyCode');
});


Route::middleware('auth:sanctum')->prefix('shift')->controller(ShiftController::class)->group(function () {
    Route::apiResource('/', ShiftController::class)->except('store', 'update');
    Route::post('/start', 'start');
    Route::post('/close', 'close');
});



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
