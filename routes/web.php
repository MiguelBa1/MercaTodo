<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\Admin\ProductController;
use App\Http\Controllers\Web\Admin\UserController;
use App\Http\Controllers\Web\Admin\AdminController;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
});

Route::middleware(['auth', 'role:admin', 'checkStatus', 'verified'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.view.users');
        Route::get('edit/{user}', [UserController::class, 'edit'])->name('admin.edit.user');
    });
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('admin.view.products');
        Route::get('create', [ProductController::class, 'create'])->name('admin.products.create');
        Route::get('{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    });
});

// Route to show a product
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

require __DIR__ . '/auth.php';
