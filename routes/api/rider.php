<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Rider\CancelRideController;
use App\Http\Controllers\Api\V1\Rider\CreateRideController;
use App\Http\Controllers\Api\V1\Rider\GetActiveRideController;
use App\Http\Controllers\Api\V1\Rider\GetRideController;
use App\Http\Controllers\Api\V1\Rider\GetRideHistoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('rider')->group(function (): void {
    Route::post('rides', CreateRideController::class)
        ->middleware(['auth:sanctum', 'verified', 'role:rider', 'profile_step:completed'])
        ->name('api.v1.rider.rides');

    Route::get('rides/active', GetActiveRideController::class)
        ->middleware(['auth:sanctum', 'verified', 'role:rider', 'profile_step:completed'])
        ->name('api.v1.rider.rides.active');

    Route::get('rides/history', GetRideHistoryController::class)
        ->middleware(['auth:sanctum', 'verified', 'role:rider', 'profile_step:completed'])
        ->name('api.v1.rider.rides.history');

    Route::get('rides/{ride}', GetRideController::class)
        ->middleware(['auth:sanctum', 'verified', 'role:rider', 'profile_step:completed'])
        ->name('api.v1.rider.rides.show');

    Route::post('rides/{ride}/cancel', CancelRideController::class)
        ->middleware(['auth:sanctum', 'verified', 'role:rider', 'profile_step:completed'])
        ->name('api.v1.rider.rides.cancel');
});
