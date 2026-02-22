<?php

declare(strict_types=1);

namespace App\Providers;

use App\Queries\Rider\GetActiveRideQuery;
use App\Queries\Rider\GetActiveRideQueryInterface;
use App\Queries\Rider\GetFavoriteLocationQuery;
use App\Queries\Rider\GetFavoriteLocationQueryInterface;
use App\Queries\Rider\GetFavoriteLocationsQuery;
use App\Queries\Rider\GetFavoriteLocationsQueryInterface;
use App\Queries\Rider\GetRideHistoryQuery;
use App\Queries\Rider\GetRideHistoryQueryInterface;
use App\Queries\Rider\GetRideStatsQuery;
use App\Queries\Rider\GetRideStatsQueryInterface;
use App\Queries\Rider\GetScheduledRidesQuery;
use App\Queries\Rider\GetScheduledRidesQueryInterface;
use App\Queries\Rider\SearchLocationsQuery;
use App\Queries\Rider\SearchLocationsQueryInterface;
use Illuminate\Support\ServiceProvider;

final class RiderServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        GetActiveRideQueryInterface::class        => GetActiveRideQuery::class,
        GetFavoriteLocationQueryInterface::class  => GetFavoriteLocationQuery::class,
        GetFavoriteLocationsQueryInterface::class => GetFavoriteLocationsQuery::class,
        GetRideHistoryQueryInterface::class       => GetRideHistoryQuery::class,
        GetRideStatsQueryInterface::class         => GetRideStatsQuery::class,
        GetScheduledRidesQueryInterface::class    => GetScheduledRidesQuery::class,
        SearchLocationsQueryInterface::class      => SearchLocationsQuery::class,
    ];
}
