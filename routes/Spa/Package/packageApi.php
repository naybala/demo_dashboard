<?php

use BasicDashboard\Spa\Packages\Controllers\PackageController;
use Illuminate\Support\Facades\Route;

Route::controller(PackageController::class)->group(function (): void {
    Route::post('/user-not-purchase-regions', 'getUserNotPurchaseRegion');
    Route::post('/order-map-price', 'orderMapPrice');

});
