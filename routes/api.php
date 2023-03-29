<?php

use App\Http\Controllers\AdminController;
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
    });
});
