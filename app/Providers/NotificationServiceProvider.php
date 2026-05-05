<?php

declare(strict_types=1);

namespace App\Providers;

use App\Queries\Notification\GetNotificationPreferenceQuery;
use App\Queries\Notification\GetNotificationPreferenceQueryInterface;
use App\Queries\Notification\GetNotificationsQuery;
use App\Queries\Notification\GetNotificationsQueryInterface;
use App\Queries\Notification\GetUnreadNotificationsCountQuery;
use App\Queries\Notification\GetUnreadNotificationsCountQueryInterface;
use Illuminate\Support\ServiceProvider;

final class NotificationServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        GetNotificationsQueryInterface::class            => GetNotificationsQuery::class,
        GetNotificationPreferenceQueryInterface::class   => GetNotificationPreferenceQuery::class,
        GetUnreadNotificationsCountQueryInterface::class => GetUnreadNotificationsCountQuery::class,
    ];
}
