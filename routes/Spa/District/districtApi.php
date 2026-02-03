<?php

use BasicDashboard\Spa\Districts\Controllers\DistrictController;
use Illuminate\Support\Facades\Route;

Route::controller(DistrictController::class)->group(function(): void{
    Route::post('/district-list','index');
    Route::get('/district/detail/{code}','show');
});
