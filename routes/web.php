<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommonController;
use App\Http\Controllers\SendMessageController;
use App\Http\Controllers\Waiter\WaiterController;
use Illuminate\Support\Facades\Route;

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

Route::get('/login', [AuthController::class, 'login'])->name('view.login');
Route::post('/post-login', [AuthController::class, 'postLogin'])->name('post.login');
Route::any('/logout', [AuthController::class, 'logout'])->name('post.logout');
/**
 * Route for admin
 */
Route::middleware('auth')->group(function () {
    Route::post('/change-table-status', [CommonController::class, 'changeTableStatus'])->name('change-table-status');
    Route::prefix('admin')->group(function () {
        Route::get('/admin-dashboard', [AdminController::class, 'dashboard'])->name('admin-dashboard');
        Route::get('/timesheet', [AdminController::class, 'timesheet'])->name('timesheet');
        Route::post('/approval-timesheet', [AdminController::class, 'approveTimesheet'])->name('post.timesheet');
        Route::get('/setting-foods', [AdminController::class, 'settingFood'])->name('admin.food');
        Route::post('set-food', [AdminController::class, 'setFood'])->name('admin.post.set-food');
    });
});
Route::middleware('auth', 'waiter')->group(function () {
    Route::prefix('waiter')->group(function () {
        Route::get('/waiter-dashboard', [WaiterController::class, 'dashboard'])->name('waiter-dashboard');
        Route::get('/{tableId}/food-list', [WaiterController::class, 'listFoodsForOrder'])->name('food-list');
        Route::get('/{tableId}/food-list/{menuId}', [WaiterController::class, 'listFoodsByMenu'])->name('food-list-by-menu');
    });
});



Route::get('/chef-dashboard', function() {
    dd('chef');
})->name('chef-dashboard');
Route::get('/cashier-dashboard', function() {
    dd('cashier');
})->name('cashier-dashboard');
Route::get('/send', [SendMessageController::class, 'index'])->name('send');
Route::post('/postMessage', [SendMessageController::class, 'sendMessage'])->name('postMessage');
