<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Device\CreateDeviceTokenController;
use App\Http\Controllers\Api\V1\Device\DeleteDeviceTokenController;
use App\Http\Controllers\Api\V1\Device\GetDeviceTokensController;
use Illuminate\Support\Facades\Route;

Route::prefix('devices')
    ->middleware([
        'auth:sanctum',
        'verified',
        'profile_step:completed',
        'set_locale',
    ])
    ->group(function (): void {
        Route::get('/', [GetDeviceTokensController::class, '__invoke'])
            ->name('api.v1.devices.index');
        Route::post('/', [CreateDeviceTokenController::class, '__invoke'])
            ->name('api.v1.devices.store');
        Route::delete('/{deviceToken}', [DeleteDeviceTokenController::class, '__invoke'])
            ->name('api.v1.devices.destroy');
    });
