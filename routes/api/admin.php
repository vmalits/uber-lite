<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Admin\AdjustUserCreditsController;
use App\Http\Controllers\Api\V1\Admin\CreatePromoCodeController;
use App\Http\Controllers\Api\V1\Admin\DeletePromoCodeController;
use App\Http\Controllers\Api\V1\Admin\DeleteUserController;
use App\Http\Controllers\Api\V1\Admin\DriverBanController;
use App\Http\Controllers\Api\V1\Admin\DriverUnbanController;
use App\Http\Controllers\Api\V1\Admin\GetAnalyticsOverviewController;
use App\Http\Controllers\Api\V1\Admin\GetDriverController;
use App\Http\Controllers\Api\V1\Admin\GetDriversController;
use App\Http\Controllers\Api\V1\Admin\GetPromoCodeController;
use App\Http\Controllers\Api\V1\Admin\GetPromoCodesController;
use App\Http\Controllers\Api\V1\Admin\GetRevenueAnalyticsController;
use App\Http\Controllers\Api\V1\Admin\GetRideController;
use App\Http\Controllers\Api\V1\Admin\GetRidesAnalyticsController;
use App\Http\Controllers\Api\V1\Admin\GetRidesController;
use App\Http\Controllers\Api\V1\Admin\GetTicketCommentsController;
use App\Http\Controllers\Api\V1\Admin\GetTicketController;
use App\Http\Controllers\Api\V1\Admin\GetTicketsController;
use App\Http\Controllers\Api\V1\Admin\GetUserController;
use App\Http\Controllers\Api\V1\Admin\GetUsersController;
use App\Http\Controllers\Api\V1\Admin\LoginController;
use App\Http\Controllers\Api\V1\Admin\UpdatePromoCodeController;

Route::prefix('admin')
    ->middleware(['set_locale'])
    ->group(function (): void {
        Route::post('login', [LoginController::class, '__invoke'])->name('api.v1.admin.login');
        Route::get('analytics/overview', [GetAnalyticsOverviewController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.analytics.overview');
        Route::get('analytics/rides', [GetRidesAnalyticsController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.analytics.rides');
        Route::get('analytics/revenue', [GetRevenueAnalyticsController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.analytics.revenue');
        Route::get('users', [GetUsersController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.users.index');
        Route::get('users/{user}', [GetUserController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.users.show');
        Route::get('drivers', [GetDriversController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.drivers.index');
        Route::get('drivers/{driver}', [GetDriverController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.drivers.show');
        Route::get('rides', [GetRidesController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.rides.index');
        Route::get('rides/{ride}', [GetRideController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.rides.show');
        Route::delete('users/{user}', [DeleteUserController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.users.destroy');
        Route::post('drivers/{driver}/ban', [DriverBanController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.drivers.ban');
        Route::post('drivers/{driver}/unban', [DriverUnbanController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.drivers.unban');
        Route::get('tickets', [GetTicketsController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.tickets.index');
        Route::get('tickets/{ticket}', [GetTicketController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.tickets.show');
        Route::get('tickets/{ticket}/comments', [GetTicketCommentsController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.tickets.comments.index');
        Route::get('promo-codes', [GetPromoCodesController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.promo-codes.index');
        Route::post('promo-codes', [CreatePromoCodeController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.promo-codes.store');
        Route::get('promo-codes/{promoCode}', [GetPromoCodeController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.promo-codes.show');
        Route::put('promo-codes/{promoCode}', [UpdatePromoCodeController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.promo-codes.update');
        Route::delete('promo-codes/{promoCode}', [DeletePromoCodeController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.promo-codes.destroy');
        Route::post('users/{user}/credits', [AdjustUserCreditsController::class, '__invoke'])
            ->middleware(['auth:sanctum', 'role:admin'])
            ->name('api.v1.admin.users.credits');
    });
