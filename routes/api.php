<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\AddEmailController;
use App\Http\Controllers\Api\V1\Auth\RequestOtpController;
use App\Http\Controllers\Api\V1\Auth\VerifyEmailController;
use App\Http\Controllers\Api\V1\Auth\VerifyOtpController;
use App\Http\Middleware\ThrottleOtpRequests;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::prefix('auth')->group(function (): void {
        Route::post('request-otp', [RequestOtpController::class, '__invoke'])
            ->middleware(ThrottleOtpRequests::class)
            ->name('api.v1.auth.request-otp');

        Route::post('verify-otp', [VerifyOtpController::class, '__invoke'])
            ->name('api.v1.auth.verify-otp');

        Route::post('add-email', [AddEmailController::class, '__invoke'])
            ->name('api.v1.auth.add-email');

        Route::get('/email/verify/{user}/{hash}', VerifyEmailController::class)
            ->name('verification.verify')
            ->middleware(['signed', 'throttle:6,1'])
            ->whereUlid('user')
            ->where('hash', '[A-Fa-f0-9]{40}');
    });
});
