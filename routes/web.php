<?php

use BasicDashboard\Web\DailyIncomes\Controllers\DailyIncomeController;
use BasicDashboard\Web\Products\Controllers\ProductController;
use BasicDashboard\Web\Units\Controllers\UnitController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use BasicDashboard\Web\Auth\Controllers\AuthController;
use BasicDashboard\Web\Roles\Controllers\RoleController;
use BasicDashboard\Web\Audits\Controllers\AuditController;
use BasicDashboard\Web\Categories\Controllers\CategoryController;
use BasicDashboard\Web\Dashboard\Controllers\DashboardController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('optimize-hey-yo', function () {
    Artisan::call('optimize:clear');
    return redirect('/');
});

require __DIR__ . "/Web/Guest/guestRoute.php";
require __DIR__ . "/Web/Localization/localizationRoute.php";

Route::group(['middleware' => ['auth', 'permission.check']], function (): void {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('categories', CategoryController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('products' ,ProductController::class);
    Route::resource('audits', AuditController::class)->only(['index', 'show']);
    Route::resource('units' ,UnitController::class);
    Route::resource('daily-incomes' ,DailyIncomeController::class);
    require __DIR__ . "/Web/User/userRoute.php";
});

require __DIR__ . "/Web/Storage/storageApi.php";


