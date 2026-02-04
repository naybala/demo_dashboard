<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1/mobile')->middleware(['accept.json'])->group(function (): void {
    require __DIR__ . "/Mobile/User/userApi.php";
});

Route::prefix('v1/spa')->middleware(['accept.json'])->group(function (): void {
    require __DIR__ . "/Spa/User/userApi.php";
});
