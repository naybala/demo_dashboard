<?php

use BasicDashboard\Mobile\Auth\Controllers\AuthController;
use BasicDashboard\Spa\Auth\Controllers\AuthController as SPAAuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/mobile')->middleware(['accept.json'])->group(function (): void {
    require __DIR__ . "/Mobile/Guest/guestApi.php";
    require __DIR__ . "/Mobile/Guest/authApi.php";
    require __DIR__ . "/Mobile/Page/pageApi.php";
    require __DIR__ . "/Mobile/Listing/listingApi.php";
    require __DIR__ . "/Mobile/BankPartner/bankPartnerApi.php";
    require __DIR__ . "/Mobile/Event/eventApi.php";
    require __DIR__ . "/Mobile/User/userApi.php";
    require __DIR__ . "/Mobile/Agent/agentApi.php";
    require __DIR__ . "/Mobile/PropertyType/propertyTypeApi.php";
    require __DIR__ . "/Mobile/MapPrice/mapPriceApi.php";
});

Route::prefix('v1/spa')->middleware(['accept.json'])->group(function (): void {
    require __DIR__ . "/Spa/Page/pageApi.php";
    require __DIR__ . "/Spa/Auth/authApi.php";
    require __DIR__ . "/Spa/Listing/listingApi.php";
    require __DIR__ . "/Spa/Province/provinceApi.php";
    require __DIR__ . "/Spa/District/districtApi.php";
    require __DIR__ . "/Spa/PropertyType/PropertyTypeApi.php";
    require __DIR__ . "/Spa/BankPartner/bankPartnerApi.php";
    require __DIR__ . "/Spa/Partner/partnerApi.php";
    require __DIR__ . "/Spa/Agent/agentApi.php";
    require __DIR__ . "/Spa/Event/eventApi.php";
    require __DIR__ . "/Spa/UserGuide/userGuideApi.php";
    require __DIR__ . "/Spa/ABAPayment/abaPaymentApi.php";
    require __DIR__ . "/Spa/User/userApi.php";
    Route::post('/register', [SPAAuthController::class, 'register']);
    require __DIR__ . "/Spa/MapPrice/freeMapPrice.php";
});

Route::prefix('v1/spa')->middleware(['accept.json', 'auth:sanctum'])->group(function (): void {
    Route::post('/dashboard', function () {
        return "This mean you are authenticated User Ha Ha";
    });
    require __DIR__ . "/Spa/MapPrice/mapPriceApi.php";
    require __DIR__ . "/Spa/Package/packageApi.php";
});

Route::prefix('v1/mobile')->middleware(['accept.json', 'auth:sanctum'])->group(function (): void {
    Route::post('/check-login', [AuthController::class, 'checkLogin']);
    require __DIR__ . "/Mobile/MapPrice/mapPriceFinalApi.php";
    require __DIR__ . "/Mobile/Package/packageApi.php";
});

Route::prefix('v1/mobile')->middleware(['accept.json', 'auth:sanctum'])->group(function (): void {
    require __DIR__ . "/Mobile/Auth/authApi.php";
});

Route::post('force-logout-by-user-id/{userId}', [AuthController::class, 'forceLogoutByUserId']);