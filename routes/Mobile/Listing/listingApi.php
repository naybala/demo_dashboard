<?php

use BasicDashboard\Mobile\Listings\Controllers\ListingController;
use Illuminate\Support\Facades\Route;

Route::controller(ListingController::class)->group(function(): void{
    Route::post('/listings/index','index');
    Route::post('/listings/detail','show');
    Route::post('/listings/retrieve-data','retrieveDataForMobile')->middleware('auth:sanctum');
    Route::post('/listings/store','store');
    Route::post('/listings/my-property','myProperty')->middleware('auth:sanctum');
    Route::get('/fetch-provinces', [ListingController::class, 'fetchProvinces']);
    Route::get('fetch-districts-by-province/{code}', [ListingController::class, 'fetchDistrictsByProvince']);
    Route::get('fetch-communes-by-district/{code}', [ListingController::class, 'fetchCommunesByDistrict']);
    Route::get('fetch-villages-by-commune/{code}', [ListingController::class, 'fetchVillagesByCommune']);
});