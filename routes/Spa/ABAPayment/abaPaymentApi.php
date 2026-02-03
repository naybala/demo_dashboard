<?php

use BasicDashboard\Spa\AbaPayments\Controllers\AbaPaymentController;
use Illuminate\Support\Facades\Route;

Route::controller(AbaPaymentController::class)->group(function (): void {
    Route::post('aba-bank/generate-qr-code/{transactionId}', 'generateQRCode');
    Route::post('aba-bank/get-order-data-by-transaction-id/{transactionId}', 'getOrderDataByTransactionId')->middleware('auth:sanctum');
    Route::post('aba-bank/return-url/{transactionId}', 'successUrl')->name('aba-bank.return-url');
    Route::post('aba-bank/cancel-url/{transactionId}', 'cancelUrl')->name('aba-bank.cancel-url')->middleware('auth:sanctum');
    Route::post('get-data-for-check-transaction/{transactionId}', 'getDataForCheckTransaction');
});
