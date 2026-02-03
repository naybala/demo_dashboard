<?php

use BasicDashboard\Spa\Users\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::controller(UserController::class)->group(function (): void {
    Route::post('/user/order-list', action: 'orderList');
    Route::post('/user/show', action: 'showUser');
    Route::post('/user/update', action: 'updateUser');
    Route::post('/user/update-password', action: 'updatePassword');
});
