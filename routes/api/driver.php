<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Driver\AcceptRideController;
use App\Http\Controllers\Api\V1\Driver\CancelRideController;
use App\Http\Controllers\Api\V1\Driver\GetAvailableRidesController;
use Illuminate\Support\Facades\Route;

Route::prefix('driver')
    ->middleware([
        'auth:sanctum',
        'verified',
        'role:driver',
        'profile_step:completed',
    ])
    ->group(function (): void {
        Route::get('rides/available', GetAvailableRidesController::class)
            ->name('api.v1.driver.rides.available');
        Route::post('rides/{ride}/accept', AcceptRideController::class)
            ->name('api.v1.driver.rides.accept');
        Route::post('rides/{ride}/cancel', CancelRideController::class)
            ->name('api.v1.driver.rides.cancel');
    });
