<?php

use BasicDashboard\Mobile\OwnProduct\Controllers\OwnProductController;
use Illuminate\Support\Facades\Route;

Route::controller(OwnProductController::class)->group(function (): void {
    Route::get('/own-products', 'index');
    Route::get('/own-products/{id}', 'show');
});
