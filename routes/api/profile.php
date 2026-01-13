<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\SetLocaleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function (): void {
    Route::post('profile/locale', SetLocaleController::class)
        ->name('api.v1.profile.locale');
});
