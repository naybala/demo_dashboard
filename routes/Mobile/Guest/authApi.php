<?php

use BasicDashboard\Mobile\Auth\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function (): void {
    Route::post('/login', 'login');
    Route::post('/get-me','getMe')->middleware('auth:sanctum');
    Route::post('/register','register');
    Route::post('/change-password','changePassword')->middleware('auth:sanctum');
    Route::post('/update-user-profile','updateProfile')->middleware('auth:sanctum');
    Route::post('/logout','logout')->middleware('auth:sanctum');
});
