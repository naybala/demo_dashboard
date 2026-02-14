<?php

use BasicDashboard\Mobile\DailyIncomes\Controllers\DailyIncomeController;
use Illuminate\Support\Facades\Route;

Route::controller(DailyIncomeController::class)->group(function (): void {
    Route::get('/daily-incomes', 'index');
    Route::post('/daily-incomes', 'store');
    Route::get('/daily-incomes/{id}', 'show');
});
