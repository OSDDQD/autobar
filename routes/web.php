<?php

use Illuminate\Support\Facades\Route;
use App\Cashbox\Http\Controllers\CategoryController;
use App\Cashbox\Http\Controllers\CartController;
use App\Cashbox\Http\Controllers\WalletController;
use App\Cashbox\Http\Controllers\ManagerController;
use App\Cashbox\Http\Controllers\PrepareController;
use App\Http\Middleware\CheckIfCheckout;
use App\Http\Middleware\MaintenanceMode;
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

Route::middleware([MaintenanceMode::class])->group(function () {
    Route::middleware([CheckIfCheckout::class])->group(function () {
        Route::get('/', [CategoryController::class, 'show'])
            ->name('cash.show');

        Route::get('/cart', [CartController::class, 'show'])
            ->name('cart.show');

        Route::get('/category/{id}', [CategoryController::class, 'category'])
            ->name('cash.category.show')
            ->where('id', '[0-9]+');

        Route::get('/cart/clear', [CartController::class, 'clear'])
            ->name('cart.clear');
    });

    Route::get('/prepare', [PrepareController::class, 'show'])
        ->name('prepare.show');

    Route::get('/ready', [PrepareController::class, 'ready'])
        ->name('ready.show');

    Route::get('/cart/checkout', [CartController::class, 'checkout'])
        ->name('cart.checkout');

    Route::post('/wallet/add', [WalletController::class, 'add']);
});

Route::get('/manager', [ManagerController::class, 'show'])
    ->name('manager.show');

Route::get('/manager/sales', [ManagerController::class, 'sales'])
    ->name('manager.sales');

Route::post('/manager/sales', [ManagerController::class, 'sales'])
    ->name('manager.sales.form');

Route::get('/manager/orders', [ManagerController::class, 'orders'])
    ->name('manager.orders');

Route::get('/manager/order/{id}', [ManagerController::class, 'order'])
    ->name('manager.order.show')
    ->where('id', '[0-9]+');

Route::get('api/option', 'App\Cashbox\Http\Controllers\Api\OptionController@index');
Route::get('api/option/{id}', 'App\Cashbox\Http\Controllers\Api\OptionController@show');

Route::get('/maintenance', function () {
    return view('cash.maintenance');
})->name('show.maintenance');

Route::get('/manager/print/{id}', [ManagerController::class, 'printer'])
    ->where('id', '[0-9]+');

Route::get('/print/', [WalletController::class, 'printer']);
Route::get('/wallet/send', [WalletController::class, 'send']);
