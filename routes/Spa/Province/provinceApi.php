<?php

use Illuminate\Support\Facades\Route;
use BasicDashboard\Spa\Provinces\Controllers\ProvinceController;

Route::controller(ProvinceController::class)->group(function(): void{
    Route::post('/province-list','index');
    Route::get('/province/detail/{code}','show');
});

