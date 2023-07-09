<?php

use App\Http\Controllers\Api\Admin\Brand\BrandController as AdminBrandController;
use App\Http\Controllers\Api\Admin\Category\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\Product\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\Product\ProductExportController as AdminProductExportController;
use App\Http\Controllers\Api\Admin\Product\ProductImportController as AdminProductImportController;
use App\Http\Controllers\Api\Admin\Product\ProductStatusController as AdminProductStatusController;
use App\Http\Controllers\Api\Admin\User\PasswordController as AdminPasswordController;
use App\Http\Controllers\Api\Admin\User\ProfileController as AdminProfileController;
use App\Http\Controllers\Api\Admin\User\UserStatusController as AdminUserStatusController;
use App\Http\Controllers\Api\Cart\CartController;
use App\Http\Controllers\Api\City\CityController;
use App\Http\Controllers\Api\Order\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum', 'role:admin', 'check.user.status', 'verified'])
    ->prefix('admin')->group(function () {
        Route::prefix('users')->group(function () {
            Route::patch('{user}/status', [AdminUserStatusController::class, 'update'])->name(
                'api.admin.users.status.update'
            );
            Route::patch('{user}/password', [AdminPasswordController::class, 'update'])->name(
                'api.admin.users.password.update'
            );
            Route::patch('{user}/profile', [AdminProfileController::class, 'update'])->name(
                'api.admin.users.profile.update'
            );
        });
        Route::prefix('products')->group(function () {
            Route::post('/', [AdminProductController::class, 'store'])->name('api.admin.products.store');
            Route::get('export', [AdminProductExportController::class, 'export'])->name(
                'api.admin.products.export'
            );
            Route::get('export/{fileName}', [AdminProductExportController::class, 'checkExport'])->name(
                'api.admin.products.export.check'
            );
            Route::get('download/{fileName}', [AdminProductExportController::class, 'download'])->name(
                'api.admin.products.export.download'
            );
            Route::post('import', [AdminProductImportController::class, 'import'])->name(
                'api.admin.products.import'
            );
            Route::get('import/{fileName}', [AdminProductImportController::class, 'checkImport'])->name(
                'api.admin.products.import.check'
            );

            Route::post('{product}', [AdminProductController::class, 'update'])->name('api.admin.products.update');
            Route::delete('{product}', [AdminProductController::class, 'destroy'])->name('api.admin.products.destroy');
            Route::patch('{product}/status', [AdminProductStatusController::class, 'update'])->name(
                'api.admin.products.status.update'
            );
        });
        Route::prefix('brands')->group(function () {
            Route::get('/', [AdminBrandController::class, 'index'])->name('api.admin.brands.index');
            Route::post('/', [AdminBrandController::class, 'store'])->name('api.admin.brands.store');
            Route::patch('{brand}', [AdminBrandController::class, 'update'])->name('api.admin.brands.update');
        });

        Route::prefix('categories')->group(function () {
            Route::get('/', [AdminCategoryController::class, 'index'])->name('api.admin.categories.index');
            Route::post('/', [AdminCategoryController::class, 'store'])->name('api.admin.categories.store');
            Route::patch('{category}', [AdminCategoryController::class, 'update'])->name('api.admin.categories.update');
        });
    });

Route::get('cities/{department_id}', [CityController::class, 'index'])->name('api.cities.index');

Route::middleware(['auth:sanctum', 'verified', 'check.user.status'])->prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('api.cart.index');
    Route::post('/add', [CartController::class, 'store'])->name('api.cart.store');
    Route::delete('/remove/{product_id}', [CartController::class, 'destroy'])->name('api.cart.destroy');
});

Route::middleware(['auth:sanctum', 'verified', 'check.user.status'])->prefix('order')->group(function () {
    Route::post('/', [OrderController::class, 'store'])->name('api.order.store');
});
