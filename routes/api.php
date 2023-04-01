<?php

use App\Http\Controllers\APIs\V1\AdminController;
use App\Http\Controllers\APIs\V1\Auth\LoginController;
use App\Http\Controllers\APIs\V1\Auth\PasswordController;
use App\Http\Controllers\APIs\V1\Auth\RegisterController;
use App\Http\Controllers\APIs\V1\ProductController;
use App\Http\Controllers\APIs\V1\UserController;
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

Route::group(['prefix' => 'v1'], function () {
    Route::prefix('admin')->group(function () {
        Route::post('/login', LoginController::class)
            ->name('admin.login');

        Route::post(
            '/create',
            RegisterController::class
        )->name('admin.create');
    });

    Route::post('/login', LoginController::class)
        ->name('user.login');

    Route::post(
        '/register',
        RegisterController::class,
    )->name('user.register');

    Route::prefix('user')->group(function () {
        Route::post(
            'forgot-password',
            [
                PasswordController::class,
                'forgotPassword'
            ]
        )
            ->name('user.forgot-password');

        Route::post(
            'reset-password-token',
            [
                PasswordController::class,
                'resetPasswordToken'
            ]
        )
            ->name('user.reset-password-token');
    });

    Route::middleware('auth:jwt')
        ->group(function () {
            Route::group(['prefix' => 'admin'], function () {
                Route::middleware('is_admin')
                    ->group(function () {
                        Route::get('logout', [AdminController::class, 'logout'])
                            ->name('admin.logout');

                        Route::put(
                            'user-edit/{uuid}',
                            [
                                AdminController::class,
                                'editUser'
                            ]
                        )
                            ->name('admin.edit-user');

                        Route::get(
                            'user-listing',
                            [
                                AdminController::class,
                                'userListing'
                            ]
                        )
                            ->name('admin.user-listing');

                        Route::delete(
                            'user-delete/{uuid}',
                            [
                                AdminController::class,
                                'deleteUser'
                            ]
                        )
                            ->name('admin.user-delete');
                    });
            });

            Route::prefix('user')->group(function () {
                Route::get(
                    '/',
                    [
                        UserController::class,
                        'viewUser'
                    ]
                )
                    ->name('user.view-user');

                Route::delete(
                    '/',
                    [
                        UserController::class,
                        'deleteUser'
                    ]
                )
                    ->name('user.delete-user');

                Route::get(
                    '/orders',
                    [
                        UserController::class,
                        'orders'
                    ]
                )
                    ->name('user.orders');

                Route::put(
                    'edit',
                    [
                        UserController::class,
                        'editUser'
                    ]
                )
                    ->name('user.edit-user');

                Route::get(
                    'logout',
                    [
                        UserController::class,
                        'logout'
                    ]
                )
                    ->name('user.logout');
            });

            Route::prefix('product')->group(function () {
                Route::post(
                    'create',
                    [
                        ProductController::class,
                        'createProduct'
                    ]
                )->name('product.create-product');

                Route::put(
                    '{uuid}',
                    [
                        ProductController::class,
                        'updateProduct'
                    ]
                )->name('product.update-product');

                Route::delete(
                    '{uuid}',
                    [
                        ProductController::class,
                        'deleteProduct'
                    ]
                )->name('product.delete-product');

                Route::get(
                    '{uuid}',
                    [
                        ProductController::class,
                        'fetchProduct'
                    ]
                )->name('product.fetch-product');
            });

            Route::get(
                'products',
                [
                    ProductController::class,
                    'fetchProducts'
                ]
            )->name('product.fetch-products');
        });
});
