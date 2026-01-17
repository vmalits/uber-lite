<?php

declare(strict_types=1);

namespace App\Providers;

use App\Queries\Rider\GetActiveRideQuery;
use App\Queries\Rider\GetActiveRideQueryInterface;
use App\Queries\Rider\GetFavoriteLocationsQuery;
use App\Queries\Rider\GetFavoriteLocationsQueryInterface;
use App\Queries\Rider\GetRideHistoryQuery;
use App\Queries\Rider\GetRideHistoryQueryInterface;
use App\Queries\Rider\GetRideStatsQuery;
use App\Queries\Rider\GetRideStatsQueryInterface;
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
        GetActiveRideQuery::class                 => GetActiveRideQuery::class,
        GetFavoriteLocationsQueryInterface::class => GetFavoriteLocationsQuery::class,
        GetFavoriteLocationsQuery::class          => GetFavoriteLocationsQuery::class,
        GetRideHistoryQueryInterface::class       => GetRideHistoryQuery::class,
        GetRideHistoryQuery::class                => GetRideHistoryQuery::class,
        GetRideStatsQueryInterface::class         => GetRideStatsQuery::class,
        GetRideStatsQuery::class                  => GetRideStatsQuery::class,
        SearchLocationsQueryInterface::class      => SearchLocationsQuery::class,
        SearchLocationsQuery::class               => SearchLocationsQuery::class,
    ];
}
