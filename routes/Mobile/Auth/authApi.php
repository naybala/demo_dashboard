<?php

use BasicDashboard\Mobile\Auth\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function (): void {
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});
