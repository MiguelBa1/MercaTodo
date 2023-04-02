<?php

use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\Admin\UserController;
use App\Http\Controllers\Web\Admin\AdminController;
use App\Http\Controllers\Api\Admin\UserController as ApiUserController;
use App\Http\Controllers\Api\Admin\ProfileController as ApiProfileController;
use \App\Http\Controllers\Api\Admin\PasswordController as ApiPasswordController;
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

Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

Route::get('/admin', function () {
    return Inertia::render('Admin/Dashboard');
})->middleware(['auth', 'role:admin', 'checkStatus', 'verified'])->name('admin.dashboard');

Route::middleware(['auth', 'checkStatus', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Render views
Route::middleware(['auth', 'role:admin', 'checkStatus', 'verified'])->prefix('admin')->group(function () {
    Route::get('users', [UserController::class, 'index'])->name('admin.view.users');
    Route::get('users/edit/{user}', [UserController::class, 'edit'])->name('admin.edit.user');
});

// API calls
Route::middleware(['auth', 'role:admin', 'checkStatus', 'verified'])->prefix('admin/api')->group(function () {
    Route::get('roles', [RoleController::class, 'list'])->name('admin.api.list.roles');
    Route::get('users', [ApiUserController::class, 'list'])->name('admin.api.list.users');
    Route::patch('users/status/{user}', [ApiUserController::class, 'update'])->name('admin.api.update.user.status');
    Route::patch('users/password/{user}', [ApiPasswordController::class, 'update'])->name('admin.api.update.user.password');
    Route::patch('users/profile/{user}', [ApiProfileController::class, 'update'])->name('admin.api.update.user.profile');
});

require __DIR__ . '/auth.php';
