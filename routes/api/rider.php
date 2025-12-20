<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Rider\CreateRideController;
use App\Http\Controllers\Api\V1\Rider\GetRideController;
use Illuminate\Support\Facades\Route;

Route::prefix('rider')->group(function (): void {
    Route::post('rides', CreateRideController::class)
        ->middleware(['auth:sanctum', 'role:rider', 'profile_step:completed'])
        ->name('api.v1.rider.rides');

    Route::get('rides/{ride}', GetRideController::class)
        ->middleware(['auth:sanctum', 'role:rider', 'profile_step:completed'])
        ->name('api.v1.rider.rides.show');
});
