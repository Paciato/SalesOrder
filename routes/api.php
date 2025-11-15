<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerApiController;
use App\Http\Controllers\Api\SalesOrderApiController;
use App\Http\Controllers\Api\SalesOrderItemApiController;

Route::middleware('api')->group(function () {

    Route::get('/test', function () {
        return response()->json(['status' => 'API Active']);
    });

    // CUSTOMER
    Route::get('/customers', [CustomerApiController::class, 'index']);
    Route::get('/customers/{id}', [CustomerApiController::class, 'show']);
    Route::post('/customers', [CustomerApiController::class, 'store']);
    Route::put('/customers/{id}', [CustomerApiController::class, 'update']);
    Route::delete('/customers/{id}', [CustomerApiController::class, 'destroy']);

    // SALES ORDER
    Route::get('/sales-orders', [SalesOrderApiController::class, 'index']);
    Route::get('/sales-orders/{id}', [SalesOrderApiController::class, 'show']);
    Route::post('/sales-orders', [SalesOrderApiController::class, 'store']);
    Route::put('/sales-orders/{id}', [SalesOrderApiController::class, 'update']);
    Route::delete('/sales-orders/{id}', [SalesOrderApiController::class, 'destroy']);

    // SALES ORDER ITEMS
    Route::get('/sales-orders/{orderId}/items', [SalesOrderItemApiController::class, 'index']);
    Route::post('/sales-orders/{orderId}/items', [SalesOrderItemApiController::class, 'store']);
});
