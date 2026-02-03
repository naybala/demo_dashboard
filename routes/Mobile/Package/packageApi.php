<?php

use BasicDashboard\Mobile\Packages\Controllers\PackageController;
use Illuminate\Support\Facades\Route;

Route::controller(PackageController::class)->group(function (): void {
    Route::post('/user-not-purchase-regions', 'getUserNotPurchaseRegion')->middleware('auth:sanctum');
    Route::post('/order-map-price', 'orderMapPrice')->middleware('auth:sanctum');
    Route::get('/check-order-status/{id}', 'checkOrder')->middleware('auth:sanctum');
    Route::get('get-data-for-check-transaction/{transactionId}', 'getDataForCheckTransaction');

});
