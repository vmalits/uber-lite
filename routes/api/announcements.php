<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Announcement\GetActiveAnnouncementsController;
use Illuminate\Support\Facades\Route;

Route::prefix('announcements')
    ->middleware([
        'auth:sanctum',
        'verified',
        'profile_step:completed',
        'set_locale',
    ])
    ->group(function (): void {
        Route::get('/', [GetActiveAnnouncementsController::class, '__invoke'])
            ->name('api.v1.announcements.index');
    });
