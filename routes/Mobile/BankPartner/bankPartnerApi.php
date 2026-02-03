<?php

use BasicDashboard\Mobile\BankPartners\Controllers\BankPartnerController;
use Illuminate\Support\Facades\Route;

Route::controller(BankPartnerController::class)->group(function():void{
    Route::post('/bankPartner/index','getBankPartners');
    Route::post('/bankpartner/detail','detail');
});