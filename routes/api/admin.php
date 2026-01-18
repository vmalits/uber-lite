<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Admin\DeleteUserController;
use App\Http\Controllers\Api\V1\Admin\DriverBanController;
use App\Http\Controllers\Api\V1\Admin\DriverUnbanController;
use App\Http\Controllers\Api\V1\Admin\GetRidesController;
use App\Http\Controllers\Api\V1\Admin\GetUserController;
use App\Http\Controllers\Api\V1\Admin\GetUsersController;
use App\Http\Controllers\Api\V1\Admin\LoginController;

Route::prefix('admin')
    ->middleware(['set_locale'])
    ->group(function (): void {
        Route::post('login', [LoginController::class, '__invoke'])->name('api.v1.admin.login');
        Route::get('users', [GetUsersController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.users.index');
        Route::get('users/{user}', [GetUserController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.users.show');
        Route::get('rides', [GetRidesController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.rides.index');
        Route::delete('users/{user}', [DeleteUserController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.users.destroy');
        Route::post('drivers/{driver}/ban', [DriverBanController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.drivers.ban');
        Route::post('drivers/{driver}/unban', [DriverUnbanController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.drivers.unban');
    });
