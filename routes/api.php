<?php

use App\Http\Controllers\APIs\AdminController;
use App\Http\Controllers\APIs\UserController;
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
        Route::post('/login', [AdminController::class, 'login'])
            ->name('admin.login');

        Route::post('/create', [AdminController::class, 'createAdmin'])
            ->name('admin.create');
    });

    Route::post('/login', [UserController::class, 'login'])
        ->name('user.login');

    Route::post('/register', [UserController::class, 'register'])
        ->name('user.register');

    Route::prefix('user')->group(function () {
        Route::post('forgot-password', [UserController::class, 'forgotPassword'])
            ->name('user.forgot-password');

        Route::post('reset-password-token', [UserController::class, 'resetPasswordToken'])
            ->name('user.reset-password-token');
    });


    Route::middleware('auth:jwt')->group(function () {
        Route::group(['prefix' => 'admin'], function () {
            Route::middleware('is_admin')->group(function () {
                Route::get('logout', [AdminController::class, 'logout'])
                    ->name('admin.logout');

                Route::put('user-edit/{uuid}', [AdminController::class, 'editUser'])
                    ->name('admin.edit-user');

                Route::get('user-listing', [AdminController::class, 'userListing'])
                    ->name('admin.user-listing');

                Route::delete('user-delete/{uuid}', [AdminController::class, 'deleteUser'])
                    ->name('admin.user-delete');
            });
        });

        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'viewUser'])
                ->name('user.view-user');

            Route::delete('/', [UserController::class, 'deleteUser'])
                ->name('user.delete-user');

            Route::get('/orders', [UserController::class, 'orders'])
                ->name('user.orders');

            Route::put('edit', [UserController::class, 'editUser'])
                ->name('user.edit-user');

            Route::get('logout', [UserController::class, 'logout'])
                ->name('user.logout');
        });
    });
});
