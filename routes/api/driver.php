<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Driver\AcceptRideController;
use App\Http\Controllers\Api\V1\Driver\AddVehicleController;
use App\Http\Controllers\Api\V1\Driver\ArrivedController;
use App\Http\Controllers\Api\V1\Driver\CancelRideController;
use App\Http\Controllers\Api\V1\Driver\CompleteRideController;
use App\Http\Controllers\Api\V1\Driver\DeleteVehicleController;
use App\Http\Controllers\Api\V1\Driver\GetActiveRideController;
use App\Http\Controllers\Api\V1\Driver\GetAvailableRidesController;
use App\Http\Controllers\Api\V1\Driver\GoOfflineController;
use App\Http\Controllers\Api\V1\Driver\GoOnlineController;
use App\Http\Controllers\Api\V1\Driver\OnTheWayController;
use App\Http\Controllers\Api\V1\Driver\ProfileController;
use App\Http\Controllers\Api\V1\Driver\StartController;
use App\Http\Controllers\Api\V1\Driver\UpdateLocationController;
use App\Http\Controllers\Api\V1\Driver\UpdateProfileController;
use App\Http\Controllers\Api\V1\Driver\UpdateVehicleController;
use App\Http\Controllers\Api\V1\Driver\UploadAvatarController;
use Illuminate\Support\Facades\Route;

Route::prefix('driver')
    ->middleware([
        'auth:sanctum',
        'verified',
        'role:driver',
        'profile_step:completed',
        'set_locale',
    ])
    ->group(function (): void {
        Route::get('profile', [ProfileController::class, '__invoke'])
            ->name('api.v1.driver.profile');
        Route::put('profile', [UpdateProfileController::class, '__invoke'])
            ->name('api.v1.driver.profile.update');
        Route::post('online', [GoOnlineController::class, '__invoke'])
            ->name('api.v1.driver.online');
        Route::post('offline', [GoOfflineController::class, '__invoke'])
            ->name('api.v1.driver.offline');
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
        Route::post('location', UpdateLocationController::class)
            ->name('api.v1.driver.location');
        Route::post('avatar', [UploadAvatarController::class, '__invoke'])
            ->name('api.v1.driver.avatar');
        Route::post('vehicle', [AddVehicleController::class, '__invoke'])
            ->name('api.v1.driver.vehicle.add');
        Route::put('vehicle/{vehicle}', [UpdateVehicleController::class, '__invoke'])
            ->name('api.v1.driver.vehicle.update');
        Route::delete('vehicle/{vehicle}', [DeleteVehicleController::class, '__invoke'])
            ->name('api.v1.driver.vehicle.delete');
    });
