<?php

use BasicDashboard\Spa\PropertyTypes\Controllers\PropertyTypeController;
use Illuminate\Support\Facades\Route;

Route::controller(PropertyTypeController::class)->group(function():void{
    Route::post('/property-types/index','index');
    Route::get('/propertyType/detail/{district}','show');
});
