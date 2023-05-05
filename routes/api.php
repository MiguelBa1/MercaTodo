<?php

use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\CategoryController;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\UserController as ApiUserController;
use App\Http\Controllers\Api\Admin\RoleController as ApiRoleController;
use App\Http\Controllers\Api\Admin\ProfileController as ApiProfileController;
use App\Http\Controllers\Api\Admin\PasswordController as ApiPasswordController;
use App\Http\Controllers\Api\Admin\ProductController as ApiProductController;
use App\Http\Controllers\Api\ImageController as ApiImageController;

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
    Route::get('roles', [ApiRoleController::class, 'index'])->name('admin.api.roles.index');
    Route::prefix('users')->group(function () {
        Route::get('/', [ApiUserController::class, 'index'])->name('admin.api.users.index');
        Route::patch('{user}/status', [ApiUserController::class, 'update'])->name('admin.api.users.status.update');
        Route::patch('{user}/password', [ApiPasswordController::class, 'update'])->name('admin.api.users.password.update');
        Route::patch('{user}/profile', [ApiProfileController::class, 'update'])->name('admin.api.users.profile.update');
    });
    Route::prefix('products')->group(function () {
        Route::get('/', [ApiProductController::class, 'index'])->name('admin.api.products.index');
        Route::post('/', [ApiProductController::class, 'store'])->name('admin.api.products.store');
        Route::post('{product}', [ApiProductController::class, 'update'])->name('admin.api.products.update');
        Route::delete('{product}', [ApiProductController::class, 'destroy'])->name('admin.api.products.destroy');
        Route::patch('{product}/status', [ApiProductController::class, 'updateStatus'])->name('admin.api.products.updateStatus');
    });
});

Route::get('cities/{department_id}', function (int $department_id) {
    return City::where('department_id', $department_id)->get();
})->name('api.list.cities');

Route::get('images/{filename}', [ApiImageController::class, 'getImage'])->name('api.get.image')->middleware();

Route::get('/brands', [BrandController::class, 'index'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('api.brands.index');

Route::get('/categories', [CategoryController::class, 'index'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('api.categories.index');
