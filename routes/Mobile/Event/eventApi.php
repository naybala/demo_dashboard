<?php

use BasicDashboard\Mobile\Events\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::controller(EventController::class)->group(function():void{
    Route::post('/event/index','index');
    Route::post('/event/detail','show');
});