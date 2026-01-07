<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Rider\CancelRideController;
use App\Http\Controllers\Api\V1\Rider\CreateRideController;
use App\Http\Controllers\Api\V1\Rider\GetActiveRideController;
use App\Http\Controllers\Api\V1\Rider\GetRideController;
use App\Http\Controllers\Api\V1\Rider\GetRideHistoryController;
use App\Http\Controllers\Api\V1\Rider\GetRideStatsController;
use App\Http\Controllers\Api\V1\Rider\RateRideController;
use Illuminate\Support\Facades\Route;

Route::prefix('rider')
    ->middleware([
        'auth:sanctum',
        'verified',
        'role:rider',
        'profile_step:completed',
    ])
    ->group(function (): void {
        Route::post('rides', [CreateRideController::class, '__invoke'])
            ->name('api.v1.rider.rides');

        Route::get('rides/active', [GetActiveRideController::class, '__invoke'])
            ->name('api.v1.rider.rides.active');

        Route::get('rides/history', [GetRideHistoryController::class, '__invoke'])
            ->name('api.v1.rider.rides.history');

        Route::get('rides/{ride}', [GetRideController::class, '__invoke'])
            ->name('api.v1.rider.rides.show');

        Route::post('rides/{ride}/cancel', [CancelRideController::class, '__invoke'])
            ->name('api.v1.rider.rides.cancel');

        Route::put('rides/{ride}/rating', [RateRideController::class, '__invoke'])
            ->name('api.v1.rider.rides.rating');

        Route::get('stats', [GetRideStatsController::class, '__invoke'])
            ->name('api.v1.rider.stats');
    });
