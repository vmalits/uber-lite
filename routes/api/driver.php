<?php

declare(strict_types=1);

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
        Route::get('rides/available', GetAvailableRidesController::class);
    });
