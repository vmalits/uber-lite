<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Notification\DeleteNotificationController;
use App\Http\Controllers\Api\V1\Notification\GetNotificationPreferenceController;
use App\Http\Controllers\Api\V1\Notification\GetNotificationsController;
use App\Http\Controllers\Api\V1\Notification\GetUnreadCountController;
use App\Http\Controllers\Api\V1\Notification\GetUnreadNotificationsController;
use App\Http\Controllers\Api\V1\Notification\MarkAllAsReadController;
use App\Http\Controllers\Api\V1\Notification\MarkAsReadController;
use App\Http\Controllers\Api\V1\Notification\UpdateNotificationPreferenceController;
use Illuminate\Support\Facades\Route;

Route::prefix('notifications')
    ->middleware([
        'auth:sanctum',
        'set_locale',
    ])
    ->group(function (): void {
        Route::get('preferences', [GetNotificationPreferenceController::class, '__invoke'])
            ->name('api.v1.notifications.preferences.show');
        Route::put('preferences', [UpdateNotificationPreferenceController::class, '__invoke'])
            ->name('api.v1.notifications.preferences.update');
        Route::get('/', [GetNotificationsController::class, '__invoke'])
            ->name('api.v1.notifications.index');
        Route::get('unread', [GetUnreadNotificationsController::class, '__invoke'])
            ->name('api.v1.notifications.unread');
        Route::get('unread/count', [GetUnreadCountController::class, '__invoke'])
            ->name('api.v1.notifications.unread.count');
        Route::put('{notification}/read', [MarkAsReadController::class, '__invoke'])
            ->name('api.v1.notifications.mark_as_read');
        Route::post('read-all', [MarkAllAsReadController::class, '__invoke'])
            ->name('api.v1.notifications.mark_all_as_read');
        Route::delete('{notification}', [DeleteNotificationController::class, '__invoke'])
            ->name('api.v1.notifications.destroy');
    });
