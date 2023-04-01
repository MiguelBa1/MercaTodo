<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
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

//Route::get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard')->middleware(['auth', 'checkStatus', 'verified']);

Route::middleware(['auth', 'checkStatus', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin', 'checkStatus', 'verified'])->group(function () {
    // Route to get roles
    Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles');
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/admin/list-users', [UserController::class, 'list'])->name('admin.list-users');
    Route::patch('/admin/manage-user-status/{user}', [UserController::class, 'manageStatus'])->name('admin.manage-user-status');
    // Route to edit user
    Route::get('/admin/edit-user/{user}', [UserController::class, 'edit'])->name('admin.edit-user');
    // Route to update user password
    Route::patch('/admin/update-user-password/{user}', [UserController::class, 'updatePassword'])->name('admin.update-user-password');
    // Route to update user profile
    Route::patch('/admin/update-user-profile/{user}', [UserController::class, 'updateProfile'])->name('admin.update-user-profile');
});

require __DIR__.'/auth.php';
