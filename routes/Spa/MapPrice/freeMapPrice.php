<?php

use BasicDashboard\Spa\MapPrices\Controllers\MapPriceController;
use Illuminate\Support\Facades\Route;

Route::controller(MapPriceController::class)->group(function (): void {
    Route::post('/map-price-region', 'getMapPriceRegion');
    // Route::post('/map-price-data', 'fetchMapPriceDataByRegionAndType');
    // Route::post('/map-price-detail', 'getMapPriceDetail');
});
