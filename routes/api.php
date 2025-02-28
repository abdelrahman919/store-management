<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route::prefix('client')->group(function () {
    
//     Route::apiResource('/', ClientController::class);
//     Route::post('{client}/allow-credit', ClientController::class, 'ClientController@allowCredit');
// });


Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::middleware('auth:sanctum')->post('/logout', 'logout');
});

Route::middleware('auth:sanctum')->controller(ClientController::class)->group(function () {
    Route::apiResource('/client', ClientController::class);
    Route::post('/client/{client}/allow-credit', 'allowCredit');
    Route::post('/client/{client}/credit_limit', 'updateCreditLimit');
});


Route::controller(SupplierController::class)->group(function () {
    Route::apiResource('supplier', SupplierController::class);
    
});


Route::controller(ItemController::class)->group(function () {
   Route::apiResource('/item', ItemController::class);
   Route::delete('/item/code/{code}', 'destroyCode');
});


Route::middleware('auth:sanctum')->controller(ShiftController::class)->group(function () {
    Route::apiResource('/shift', ShiftController::class)->except('store', 'update');
    Route::post('/shift/start', 'start');
    Route::post('/shift/close', 'close');
});


Route::middleware('auth:sanctum')->controller(PurchaseOrderController::class)->group(function () {
    Route::apiResource('/order', PurchaseOrderController::class);
});

Route::middleware('auth:sanctum')->controller(TransactionController::class)->group(function () {
    Route::apiResource('/transaction', TransactionController::class)->except('update', 'destroy');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
