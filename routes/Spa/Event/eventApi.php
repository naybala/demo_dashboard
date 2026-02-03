<?php

use BasicDashboard\Spa\Events\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::controller(EventController::class)->group(function (): void {
    Route::post('/event-listing', 'index');
    Route::post('/event-detail', 'show');
});
