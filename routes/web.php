<?php

use Illuminate\Support\Facades\Route;
use App\Cashbox\Http\Controllers\CategoryController;
use App\Cashbox\Http\Controllers\CartController;
use App\Cashbox\Http\Controllers\WalletController;
use App\Cashbox\Http\Controllers\ManagerController;
use App\Cashbox\Http\Controllers\PrepareController;
use App\Http\Middleware\CheckIfCheckout;
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

Route::middleware([CheckIfCheckout::class])->group(function () {
    Route::get('/', [CategoryController::class, 'show'])
        ->name('cash.show');

    Route::get('/cart', [CartController::class, 'show'])
        ->name('cart.show');

    Route::get('/prepare', [PrepareController::class, 'show'])
        ->name('prepare.show')
        ->withoutMiddleware([CheckIfCheckout::class]);

    Route::get('/category/{id}', [CategoryController::class, 'category'])
        ->name('cash.category.show')
        ->where('id', '[0-9]+');

    Route::get('/cart/checkout', [CartController::class, 'checkout'])
        ->name('cart.checkout')
        ->withoutMiddleware([CheckIfCheckout::class]);
});

Route::get('/manager', [ManagerController::class, 'show'])
    ->name('manager.show');
Route::post('/wallet/add', [WalletController::class, 'add']);
Route::get('/wallet/test', [WalletController::class, 'test']);
Route::get('/wallet/reset', [WalletController::class, 'reset']);
Route::get('/send', [WalletController::class, 'send']);
