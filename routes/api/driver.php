<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Driver\AcceptRideController;
use App\Http\Controllers\Api\V1\Driver\ArrivedController;
use App\Http\Controllers\Api\V1\Driver\CancelRideController;
use App\Http\Controllers\Api\V1\Driver\CompleteRideController;
use App\Http\Controllers\Api\V1\Driver\GetActiveRideController;
use App\Http\Controllers\Api\V1\Driver\GetAvailableRidesController;
use App\Http\Controllers\Api\V1\Driver\OnTheWayController;
use App\Http\Controllers\Api\V1\Driver\StartController;
use Illuminate\Support\Facades\Route;

Route::prefix('driver')
    ->middleware([
        'auth:sanctum',
        'verified',
        'role:driver',
        'profile_step:completed',
    ])
    ->group(function (): void {
        Route::get('rides/active', [GetActiveRideController::class, '__invoke'])
            ->name('api.v1.driver.rides.active');
        Route::get('rides/available', [GetAvailableRidesController::class, '__invoke'])
            ->name('api.v1.driver.rides.available');
        Route::post('rides/{ride}/accept', [AcceptRideController::class, '__invoke'])
            ->name('api.v1.driver.rides.accept');
        Route::post('rides/{ride}/on-the-way', [OnTheWayController::class, '__invoke'])
            ->name('api.v1.driver.rides.on-the-way');
        Route::post('rides/{ride}/arrived', [ArrivedController::class, '__invoke'])
            ->name('api.v1.driver.rides.arrived');
        Route::post('rides/{ride}/start', [StartController::class, '__invoke'])
            ->name('api.v1.driver.rides.start');
        Route::post('rides/{ride}/complete', [CompleteRideController::class, '__invoke'])
            ->name('api.v1.driver.rides.complete');
        Route::post('rides/{ride}/cancel', [CancelRideController::class, '__invoke'])
            ->name('api.v1.driver.rides.cancel');
    });
