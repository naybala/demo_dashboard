<?php

use BasicDashboard\Spa\Partners\Controllers\PartnerController;
use Illuminate\Support\Facades\Route;

Route::controller(PartnerController::class)->group(function (): void {
    Route::post('/business-partner-list', 'index');
});
