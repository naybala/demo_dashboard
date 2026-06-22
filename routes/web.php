<?php

use BasicDashboard\Web\DailyIncomes\Controllers\DailyIncomeController;
use BasicDashboard\Web\OwnProducts\Controllers\OwnProductController;
use BasicDashboard\Web\Permissions\Controllers\PermissionController;
use BasicDashboard\Web\Products\Controllers\ProductController;
use BasicDashboard\Web\Units\Controllers\UnitController;
use BasicDashboard\Web\Warehouses\Controllers\WarehouseController;
use BasicDashboard\Web\Inventories\Controllers\InventoryController;
use BasicDashboard\Web\StockTransactions\Controllers\StockTransactionController;
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

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

Route::get('optimize-hey-yo', function () {
    Artisan::call('optimize:clear');
    return redirect('/');
});

require __DIR__ . "/Web/Guest/guestRoute.php";
require __DIR__ . "/Web/Localization/localizationRoute.php";

Route::group(['middleware' => ['auth', 'permission.check']], function (): void {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('categories', CategoryController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('products' ,ProductController::class);
    Route::resource('audits', AuditController::class)->only(['index', 'show']);
    Route::resource('units' ,UnitController::class);
    Route::get('daily-incomes/import', [DailyIncomeController::class, 'importView'])->name('daily-incomes.import-view');
    Route::post('daily-incomes/import', [DailyIncomeController::class, 'import'])->name('daily-incomes.import');
    Route::get('daily-incomes/sample-excel', [DailyIncomeController::class, 'downloadSample'])->name('daily-incomes.sample-excel');
    Route::resource('daily-incomes' ,DailyIncomeController::class);
    Route::get('own-products/pos', [OwnProductController::class, 'posProducts'])->name('own-products.pos');
    Route::get('own-products/search', [OwnProductController::class, 'search'])->name('own-products.search');
    Route::resource('own-products' ,OwnProductController::class);

    // Warehouse / Inventory / Stock Management
    Route::get('warehouses/search', [WarehouseController::class, 'search'])->name('warehouses.search');
    Route::resource('warehouses', WarehouseController::class)->except(['show', 'create', 'edit']);
    Route::get('inventories/check-stock', [InventoryController::class, 'checkStock'])->name('inventories.check-stock');
    Route::get('inventories', [InventoryController::class, 'index'])->name('inventories.index');
    Route::get('stock-transactions', [StockTransactionController::class, 'index'])->name('stock-transactions.index');
    Route::post('stock-transactions', [StockTransactionController::class, 'store'])->name('stock-transactions.store');

    require __DIR__ . "/Web/User/userRoute.php";
});

require __DIR__ . "/Web/Storage/storageApi.php";


