<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1/mobile')->middleware(['accept.json','auth:sanctum'])->group(function (): void {
    require __DIR__ . "/Mobile/Category/categoryApi.php";
    require __DIR__ . "/Mobile/OwnProduct/ownProductApi.php";
    require __DIR__ . "/Mobile/DailyIncome/dailyIncomeApi.php";
});

Route::prefix('v1/mobile')->middleware(['accept.json'])->group(function (): void {
    require __DIR__ . "/Mobile/Auth/authApi.php";
});

Route::prefix('v1/spa')->middleware(['accept.json'])->group(function (): void {
    require __DIR__ . "/Spa/User/userApi.php";
    require __DIR__ . "/Spa/Category/categoryApi.php";
});
