<?php

use BasicDashboard\Spa\Auth\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function (): void {
    Route::post('/login', 'login');
    Route::post('/get-me', 'getMe');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});
