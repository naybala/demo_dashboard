<?php

use BasicDashboard\Mobile\PropertyTypes\Controllers\PropertyTypeController;
use Illuminate\Support\Facades\Route;


Route::controller(PropertyTypeController::class)->group(function(): void{
    // Route::post('/property-types/search','index');
});