<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\Admin\AuxiliaryTablesController;
use App\Http\Controllers\Web\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Web\Admin\UserController as AdminUserController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\Admin\DashboardController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\ProductDetailController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\PaymentController;
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

Route::get(
    '/',
    [HomeController::class, 'index']
)->name('home');

Route::middleware(['auth', 'checkStatus', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');

    Route::prefix('/payment')->group(function () {
        Route::get('/result/{order}', [PaymentController::class, 'handleRedirect'])->name('payment.result');
        Route::get('/canceled/{order}', [PaymentController::class, 'handleCanceled'])->name('payment.canceled');
        Route::get('/retry/{order}', [PaymentController::class, 'retryPayment'])->name('payment.retry');
    });
});

Route::middleware(['auth', 'role:admin', 'checkStatus', 'verified'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::prefix('users')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('admin.view.users');
        Route::get('edit/{user}', [AdminUserController::class, 'edit'])->name('admin.edit.user');
    });
    Route::prefix('products')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('admin.view.products');
        Route::get('create', [AdminProductController::class, 'create'])->name('admin.products.create');
        Route::get('{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    });

    Route::get('/auxiliary-tables', [AuxiliaryTablesController::class, 'index'])->name('admin.auxiliary.tables.index');
});

Route::get('/products/{product}', [ProductDetailController::class, 'show'])->name('products.show');

require __DIR__ . '/auth.php';
