<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Driver\AcceptRideController;
use App\Http\Controllers\Api\V1\Driver\CancelRideController;
use App\Http\Controllers\Api\V1\Driver\GetActiveRideController;
use App\Http\Controllers\Api\V1\Driver\GetAvailableRidesController;
use App\Http\Controllers\Api\V1\Driver\OnTheWayController;
use Illuminate\Support\Facades\Route;

Route::prefix('driver')
    ->middleware([
        'auth:sanctum',
        'verified',
        'role:driver',
        'profile_step:completed',
    ])
    ->group(function (): void {
        Route::get('rides/active', GetActiveRideController::class)
            ->name('api.v1.driver.rides.active');
        Route::get('rides/available', GetAvailableRidesController::class)
            ->name('api.v1.driver.rides.available');
        Route::post('rides/{ride}/accept', AcceptRideController::class)
            ->name('api.v1.driver.rides.accept');
        Route::post('rides/{ride}/on-the-way', OnTheWayController::class)
            ->name('api.v1.driver.rides.on-the-way');
        Route::post('rides/{ride}/cancel', CancelRideController::class)
            ->name('api.v1.driver.rides.cancel');
    });
