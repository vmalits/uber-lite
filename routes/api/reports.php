<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Report\CreateReportController;
use App\Http\Controllers\Api\V1\Report\GetUserReportController;
use App\Http\Controllers\Api\V1\Report\GetUserReportsController;
use Illuminate\Support\Facades\Route;

Route::prefix('reports')
    ->middleware([
        'auth:sanctum',
        'verified',
        'profile_step:completed',
        'set_locale',
    ])
    ->group(function (): void {
        Route::get('/', [GetUserReportsController::class, '__invoke'])
            ->name('api.v1.reports.index');
        Route::post('/', [CreateReportController::class, '__invoke'])
            ->name('api.v1.reports.store');
        Route::get('/{report}', [GetUserReportController::class, '__invoke'])
            ->name('api.v1.reports.show');
    });
