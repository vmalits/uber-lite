<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Gamification\GetDriverLeaderboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('leaderboard')
    ->middleware([
        'auth:sanctum',
        'verified',
        'set_locale',
    ])
    ->group(function (): void {
        Route::get('drivers', [GetDriverLeaderboardController::class, '__invoke'])
            ->name('api.v1.leaderboard.drivers');
    });
