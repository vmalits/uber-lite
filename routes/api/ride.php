<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Ride\CreateRideMessageController;
use App\Http\Controllers\Api\V1\Ride\GetRideMessagesController;
use App\Http\Controllers\Api\V1\Ride\GetRideSplitsController;
use App\Http\Controllers\Api\V1\Ride\MarkMessagesReadController;
use App\Http\Controllers\Api\V1\Ride\SplitRideController;
use Illuminate\Support\Facades\Route;

Route::prefix('ride')
    ->middleware([
        'auth:sanctum',
        'verified',
        'set_locale',
    ])
    ->group(function (): void {
        Route::get('{ride}/splits', [GetRideSplitsController::class, '__invoke'])
            ->name('api.v1.ride.splits');
        Route::post('{ride}/split', [SplitRideController::class, '__invoke'])
            ->name('api.v1.ride.split');
        Route::get('{ride}/messages', [GetRideMessagesController::class, '__invoke'])
            ->name('api.v1.ride.messages.index');
        Route::post('{ride}/messages', [CreateRideMessageController::class, '__invoke'])
            ->name('api.v1.ride.messages.store');
        Route::put('{ride}/messages/read', [MarkMessagesReadController::class, '__invoke'])
            ->name('api.v1.ride.messages.read');
    });
