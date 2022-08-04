<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AggregationController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Cashier\CashierController;
use App\Http\Controllers\Chef\ChefController;
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
    Route::get('/change-password', [CommonController::class, 'index'])->name('view.change-password');
    Route::post('/change-password', [CommonController::class, 'changePassword'])->name('post.change-password');
    Route::post('/change-table-status', [CommonController::class, 'changeTableStatus'])->name('change-table-status');
    Route::post('/add-food-to-order', [CommonController::class, 'addFoodToOrder'])->name('add-food');
    Route::post('/create-new-order', [CommonController::class, 'createOrder'])->name('create-order');
    Route::post('/create-new-sub-order', [CommonController::class, 'createSubOrder'])->name('create-sub-order');
    Route::post('/send-message', [SendMessageController::class, 'sendMessage'])->name('send-message');
    Route::post('/remove-order-food', [CommonController::class, 'removeOrderFood'])->name('remove-order-food');
    Route::post('/change-food-to-table-status', [CommonController::class, 'changeToTableStatus'])->name('to-table-status');
    Route::post('/paid-order', [CommonController::class, 'paidOrder'])->name('paid-order');
    Route::middleware('admin')->group(function () {
        Route::prefix('admin')->group(function () {
            Route::get('/admin-dashboard', [AdminController::class, 'dashboard'])->name('admin-dashboard');
            Route::get('/timesheet', [AdminController::class, 'timesheet'])->name('timesheet');
            Route::post('/approval-timesheet', [AdminController::class, 'approveTimesheet'])->name('post.timesheet');
            Route::get('/setting-foods', [AdminController::class, 'settingFood'])->name('admin.food');
            Route::post('/set-food', [AdminController::class, 'setFood'])->name('admin.post.set-food');
            Route::post('/delete-food', [AdminController::class, 'deleteFood'])->name('admin.post.delete-food');
            Route::get('/thong-ke-doanh-so', [AggregationController::class, 'aggregateOrder'])->name('admin.aggOrder');
            Route::post('/thong-ke-doanh-so-custom', [AggregationController::class, 'aggregateOrderCustom'])->name('aggOrder-custom');
            Route::get('/list-employee', [EmployeeController::class, 'list'])->name('list-employee');
            Route::get('/employee', [EmployeeController::class, 'create'])->name('view-create-employee');
            Route::post('/employee', [EmployeeController::class, 'postCreate'])->name('create-employee');
            Route::get('/employee/{id}', [EmployeeController::class, 'edit'])->name('view-edit-employee');
            Route::post('/employee/{id}', [EmployeeController::class, 'postEdit'])->name('edit-employee');
            Route::get('/employee/delete/{id}', [EmployeeController::class, 'deleteEmployee'])->name('delete-employee');
            Route::get('/list-food', [FoodController::class, 'list'])->name('list-food');
            Route::get('/food', [FoodController::class, 'create'])->name('view-create-food');
            Route::post('/food', [FoodController::class, 'postCreate'])->name('create-food');
            Route::get('/food/{id}', [FoodController::class, 'edit'])->name('view-edit-food');
            Route::post('/food/{id}', [FoodController::class, 'postEdit'])->name('edit-food');
            Route::get('/food/delete/{id}', [FoodController::class, 'deleteEmployee'])->name('delete-food');
            Route::get('/reset-table', [AdminController::class, 'resetTable'])->name('reset-table');
            // as waiter
            Route::get('/{tableId}/{orderId}', [WaiterController::class, 'orderTable'])->name('admin.view.order');
        });
    });

    Route::middleware(['waiter'])->group(function () {
        Route::prefix('waiter')->group(function () {
            Route::get('/waiter-dashboard', [WaiterController::class, 'dashboard'])->name('waiter-dashboard');
            Route::get('/{tableId}/{orderId}/food-list', [WaiterController::class, 'listFoodsForOrder'])->name('food-list');
            Route::get('/{tableId}/{orderId}/food-list/{menuId}', [WaiterController::class, 'listFoodsByMenu'])->name('food-list-by-menu');
            Route::get('/{tableId}/{orderId}', [WaiterController::class, 'orderTable'])->name('view.order');
            Route::get('/food-stand', [WaiterController::class, 'foodStand'])->name('food-stand');
            Route::get('/detail/order/{orderId}', [WaiterController::class, 'detailOrder'])->name('waiter.detail-order');
            Route::post('/update-final/{orderId}', [WaiterController::class, 'updateFinal'])->name('update-final');
            Route::post('/re-edit-order/{orderId}', [WaiterController::class, 'reEditOrder'])->name('re-edit-order');
        });
    });

    Route::middleware(['chef'])->group(function () {
        Route::prefix('chef')->group(function () {
            Route::get('/chef-dashboard/{category}', [ChefController::class, 'dashboard'])->name('chef-dashboard');
            Route::get('/setting-foods', [AdminController::class, 'settingFood'])->name('food');
            Route::post('/set-food', [AdminController::class, 'setFood'])->name('post.set-food');
            Route::post('/delete-food', [AdminController::class, 'deleteFood'])->name('post.delete-food');
            Route::post('/cancel-cooking', [ChefController::class, 'cancelCooking'])->name('cancel-cooking');
        });
    });

    Route::middleware(['cashier'])->group(function () {
        Route::prefix('cashier')->group(function () {
            Route::get('/dashboard', [CashierController::class, 'dashboard'])->name('cashier-dashboard');
            Route::get('/detail/{orderId}', [CashierController::class, 'detailOrder'])->name('cashier.detail-order');
            Route::post('/checkout/{orderId}', [CashierController::class, 'checkout'])->name('checkout');
        });
    });

    Route::get('/wait-for-approve-to-work', [AuthController::class, 'waitForApproval'])->name('wait-for-approve');
});
