<?php

use BasicDashboard\Mobile\Users\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function(): void{
    Route::post('/users/search','index');
});