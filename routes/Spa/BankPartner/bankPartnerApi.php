<?php

use BasicDashboard\Spa\BankPartners\Controllers\BankPartnerController;
use Illuminate\Support\Facades\Route;

Route::controller(BankPartnerController::class)->group(function (): void {
    Route::post('/bank-partner-list', 'index');
});
