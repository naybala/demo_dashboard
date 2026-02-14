<?php

use BasicDashboard\Mobile\OwnProducts\Controllers\OwnProductController;
use Illuminate\Support\Facades\Route;

Route::controller(OwnProductController::class)->group(function (): void {
    Route::get('/own-products', 'index');
    Route::get('/own-products/{id}', 'show');
});
