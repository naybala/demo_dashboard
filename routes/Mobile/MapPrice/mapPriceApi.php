<?php

use BasicDashboard\Mobile\MapPrices\Controllers\MapPriceController;
use Illuminate\Support\Facades\Route;


Route::controller(MapPriceController::class)->group(function(): void{
    Route::get('map-price-regions','mapPriceRegions');
    Route::get('map-prices','index');
    Route::get('get-all-map-prices','getAllMapPrices');
    Route::get('map-prices/detail','show');
    Route::get('get-nearest-point','getNearestPoint');
});