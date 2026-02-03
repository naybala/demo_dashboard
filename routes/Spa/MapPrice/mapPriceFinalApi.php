<?php

use BasicDashboard\Mobile\MapPrices\Controllers\MapPriceController;
use Illuminate\Support\Facades\Route;


Route::controller(MapPriceController::class)->group(function(): void{
    Route::get('map-price/fetch-by-region-and-type','fetchMapPriceDataByRegionAndType');
});