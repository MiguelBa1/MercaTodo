<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\UserController as ApiUserController;
use App\Http\Controllers\Api\Admin\RoleController as ApiRoleController;
use App\Http\Controllers\Api\Admin\ProfileController as ApiProfileController;
use \App\Http\Controllers\Api\Admin\PasswordController as ApiPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'role:admin', 'checkStatus', 'verified'])->prefix('admin')->group(function () {
    Route::get('roles', [ApiRoleController::class, 'list'])->name('admin.api.list.roles');
    Route::get('users', [ApiUserController::class, 'list'])->name('admin.api.list.users');
    Route::patch('users/status/{user}', [ApiUserController::class, 'update'])->name('admin.api.update.user.status');
    Route::patch('users/password/{user}', [ApiPasswordController::class, 'update'])->name('admin.api.update.user.password');
    Route::patch('users/profile/{user}', [ApiProfileController::class, 'update'])->name('admin.api.update.user.profile');
});
