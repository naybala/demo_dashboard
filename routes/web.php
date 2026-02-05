<?php

use BasicDashboard\Web\Products\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Request;
use BasicDashboard\Web\Auth\Controllers\AuthController;
use BasicDashboard\Web\Roles\Controllers\RoleController;
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
    require __DIR__ . "/Web/User/userRoute.php";
});

//for description rich editor toupload to digitalocean directly after user click and upload photo.
Route::post('upload/image', function (Request $request) {
    $path    = request()->input('path');
    $file    = request()->file('image');
    $url     = uploadImageToDigitalOcean($file, $path); //get file path that store in digitalocean
    $fullURL = config('filesystems.disks.digitalocean.endpoint') . '/' . $url;
    return response()->json([
        'data' => 'succeess',
        'code' => 200,
        'url'  => $fullURL,
    ]);
})->name('uploadImage');


