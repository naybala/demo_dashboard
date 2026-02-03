<?php

use BasicDashboard\Spa\Listings\Controllers\ListingController;
use Illuminate\Support\Facades\Route;

Route::controller(ListingController::class)->group(function (): void {
    Route::post('/property-listing', 'index');
    Route::post('/property-listing/show', 'show');
    Route::post('/retrieve-enum-data', 'retrieveEnumData');
    Route::post('/list-count', 'listCount');
});
