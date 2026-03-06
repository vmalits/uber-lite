<?php

declare(strict_types=1);

namespace App\Providers;

use App\Queries\Rider\GetActiveRideQuery;
use App\Queries\Rider\GetActiveRideQueryInterface;
use App\Queries\Rider\GetCreditBalanceQuery;
use App\Queries\Rider\GetCreditBalanceQueryInterface;
use App\Queries\Rider\GetFareBreakdownQuery;
use App\Queries\Rider\GetFareBreakdownQueryInterface;
use App\Queries\Rider\GetFavoriteDriversQuery;
use App\Queries\Rider\GetFavoriteDriversQueryInterface;
use App\Queries\Rider\GetFavoriteLocationQuery;
use App\Queries\Rider\GetFavoriteLocationQueryInterface;
use App\Queries\Rider\GetFavoriteLocationsQuery;
use App\Queries\Rider\GetFavoriteLocationsQueryInterface;
use App\Queries\Rider\GetFavoriteRoutesQuery;
use App\Queries\Rider\GetFavoriteRoutesQueryInterface;
use App\Queries\Rider\GetPaymentMethodsQuery;
use App\Queries\Rider\GetPaymentMethodsQueryInterface;
use App\Queries\Rider\GetReceiptQuery;
use App\Queries\Rider\GetReceiptQueryInterface;
use App\Queries\Rider\GetReceiptsQuery;
use App\Queries\Rider\GetReceiptsQueryInterface;
use App\Queries\Rider\GetRideHistoryQuery;
use App\Queries\Rider\GetRideHistoryQueryInterface;
use App\Queries\Rider\GetRideStatsQuery;
use App\Queries\Rider\GetRideStatsQueryInterface;
use App\Queries\Rider\GetScheduledRidesQuery;
use App\Queries\Rider\GetScheduledRidesQueryInterface;
use App\Queries\Rider\SearchLocationsQuery;
use App\Queries\Rider\SearchLocationsQueryInterface;
use App\Queries\Streak\GetStreakHistoryQuery;
use App\Queries\Streak\GetStreakHistoryQueryInterface;
use Illuminate\Support\ServiceProvider;

final class RiderServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        GetActiveRideQueryInterface::class        => GetActiveRideQuery::class,
        GetCreditBalanceQueryInterface::class     => GetCreditBalanceQuery::class,
        GetFareBreakdownQueryInterface::class     => GetFareBreakdownQuery::class,
        GetFavoriteDriversQueryInterface::class   => GetFavoriteDriversQuery::class,
        GetFavoriteLocationQueryInterface::class  => GetFavoriteLocationQuery::class,
        GetFavoriteLocationsQueryInterface::class => GetFavoriteLocationsQuery::class,
        GetFavoriteRoutesQueryInterface::class    => GetFavoriteRoutesQuery::class,
        GetPaymentMethodsQueryInterface::class    => GetPaymentMethodsQuery::class,
        GetReceiptQueryInterface::class           => GetReceiptQuery::class,
        GetReceiptsQueryInterface::class          => GetReceiptsQuery::class,
        GetRideHistoryQueryInterface::class       => GetRideHistoryQuery::class,
        GetRideStatsQueryInterface::class         => GetRideStatsQuery::class,
        GetScheduledRidesQueryInterface::class    => GetScheduledRidesQuery::class,
        SearchLocationsQueryInterface::class      => SearchLocationsQuery::class,
        GetStreakHistoryQueryInterface::class     => GetStreakHistoryQuery::class,
    ];
}
