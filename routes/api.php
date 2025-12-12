<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\RequestOtpController;
use App\Http\Middleware\ThrottleOtpRequests;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::post('request-otp', [RequestOtpController::class, '__invoke'])
            ->middleware(ThrottleOtpRequests::class)
            ->name('api.v1.auth.request-otp');
    });
});
