<?php

use BasicDashboard\Spa\Categories\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::controller(CategoryController::class)->group(function(): void{
    Route::get('/categories','index');
    Route::get('/categories/{id}','show');
    Route::get('/fetch-all-categories','fetchAll');
});