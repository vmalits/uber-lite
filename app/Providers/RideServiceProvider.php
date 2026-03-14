<?php

declare(strict_types=1);

namespace App\Providers;

use App\Queries\Ride\GetRideMessagesQuery;
use App\Queries\Ride\GetRideMessagesQueryInterface;
use Illuminate\Support\ServiceProvider;

final class RideServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        GetRideMessagesQueryInterface::class => GetRideMessagesQuery::class,
    ];
}
