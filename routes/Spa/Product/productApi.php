<?php

use BasicDashboard\Spa\Products\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::controller(ProductController::class)->group(function(): void{
    Route::get('/home','fetchHomeData');
    Route::get('/products','index');
    Route::get('/products/{id}','show');
});