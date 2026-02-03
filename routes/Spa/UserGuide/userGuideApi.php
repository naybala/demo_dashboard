<?php

use BasicDashboard\Spa\UserGuides\Controllers\UserGuideController;
use Illuminate\Support\Facades\Route;

Route::controller(UserGuideController::class)->group(function (): void {
    Route::post('/user-guides/show', 'show');
});
