<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\AddEmailController;
use App\Http\Controllers\Api\V1\Auth\CompleteProfileController;
use App\Http\Controllers\Api\V1\Auth\DeleteAccountController;
use App\Http\Controllers\Api\V1\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Api\V1\Auth\LogoutController;
use App\Http\Controllers\Api\V1\Auth\RequestOtpController;
use App\Http\Controllers\Api\V1\Auth\ResendOtpController;
use App\Http\Controllers\Api\V1\Auth\SelectRoleController;
use App\Http\Controllers\Api\V1\Auth\VerifyEmailController;
use App\Http\Controllers\Api\V1\Auth\VerifyOtpController;
use App\Http\Middleware\ThrottleOtpRequests;
use App\Http\Middleware\ThrottleOtpVerifyAttempts;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('request-otp', [RequestOtpController::class, '__invoke'])
        ->middleware(ThrottleOtpRequests::class)
        ->name('api.v1.auth.request-otp');

    Route::post('request-otp/resend', [ResendOtpController::class, '__invoke'])
        ->middleware(ThrottleOtpRequests::class)
        ->name('api.v1.auth.request-otp.resend');

    Route::post('verify-otp', [VerifyOtpController::class, '__invoke'])
        ->middleware(ThrottleOtpVerifyAttempts::class)
        ->name('api.v1.auth.verify-otp');

    Route::post('add-email', [AddEmailController::class, '__invoke'])
        ->middleware('auth:sanctum')
        ->name('api.v1.auth.add-email');

    Route::post('select-role', [SelectRoleController::class, '__invoke'])
        ->middleware('auth:sanctum')
        ->name('api.v1.auth.select-role');

    Route::post('complete-profile', [CompleteProfileController::class, '__invoke'])
        ->middleware('auth:sanctum')
        ->name('api.v1.auth.complete-profile');

    Route::post('logout', [LogoutController::class, '__invoke'])
        ->middleware('auth:sanctum')
        ->name('api.v1.auth.logout');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, '__invoke'])
        ->middleware(['auth:sanctum', 'throttle:6,1'])
        ->name('api.v1.auth.email.verification-notification');

    Route::get('/email/verify/{user}/{hash}', VerifyEmailController::class)
        ->name('verification.verify')
        ->middleware(['signed', 'throttle:6,1'])
        ->whereUlid('user')
        ->where('hash', '[A-Fa-f0-9]{40}');

    Route::delete('delete-account', [DeleteAccountController::class, '__invoke'])
        ->middleware(['auth:sanctum', 'verified'])
        ->name('api.v1.auth.delete-account');
});
