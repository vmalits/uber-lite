<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Ride\SplitRideController;
use Illuminate\Support\Facades\Route;

Route::prefix('ride')
    ->middleware([
        'auth:sanctum',
        'verified',
        'set_locale',
    ])
    ->group(function (): void {
        Route::post('{ride}/split', [SplitRideController::class, '__invoke'])
            ->name('api.v1.ride.split');
    });
