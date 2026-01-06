<?php

declare(strict_types=1);

namespace App\Providers;

use App\Queries\Rider\GetActiveRideQuery;
use App\Queries\Rider\GetActiveRideQueryInterface;
use App\Queries\Rider\GetRideHistoryQuery;
use App\Queries\Rider\GetRideHistoryQueryInterface;
use App\Queries\Rider\GetRideStatsQuery;
use App\Queries\Rider\GetRideStatsQueryInterface;
use Illuminate\Support\ServiceProvider;

final class RiderServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        GetActiveRideQueryInterface::class  => GetActiveRideQuery::class,
        GetActiveRideQuery::class           => GetActiveRideQuery::class,
        GetRideHistoryQueryInterface::class => GetRideHistoryQuery::class,
        GetRideHistoryQuery::class          => GetRideHistoryQuery::class,
        GetRideStatsQueryInterface::class   => GetRideStatsQuery::class,
        GetRideStatsQuery::class            => GetRideStatsQuery::class,
    ];
}
