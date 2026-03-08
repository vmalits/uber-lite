<?php

declare(strict_types=1);

namespace App\Providers;

use App\Presenters\Admin\DriverProfilePresenter;
use App\Presenters\Admin\DriverProfilePresenterInterface;
use App\Presenters\Admin\RidesCsvExportPresenter;
use App\Presenters\Admin\RidesCsvExportPresenterInterface;
use App\Queries\Admin\ExportRidesQuery;
use App\Queries\Admin\ExportRidesQueryInterface;
use App\Queries\Admin\GetAnalyticsOverviewQuery;
use App\Queries\Admin\GetAnalyticsOverviewQueryInterface;
use App\Queries\Admin\GetDriverQuery;
use App\Queries\Admin\GetDriverQueryInterface;
use App\Queries\Admin\GetDriversQuery;
use App\Queries\Admin\GetDriversQueryInterface;
use App\Queries\Admin\GetPromoCodesQuery;
use App\Queries\Admin\GetPromoCodesQueryInterface;
use App\Queries\Admin\GetRealTimeAnalyticsQuery;
use App\Queries\Admin\GetRealTimeAnalyticsQueryInterface;
use App\Queries\Admin\GetRevenueAnalyticsQuery;
use App\Queries\Admin\GetRevenueAnalyticsQueryInterface;
use App\Queries\Admin\GetRideQuery;
use App\Queries\Admin\GetRideQueryInterface;
use App\Queries\Admin\GetRidesAnalyticsQuery;
use App\Queries\Admin\GetRidesAnalyticsQueryInterface;
use App\Queries\Admin\GetRidesQuery;
use App\Queries\Admin\GetRidesQueryInterface;
use App\Queries\Admin\GetTicketsQuery;
use App\Queries\Admin\GetTicketsQueryInterface;
use App\Queries\Admin\GetUserQuery;
use App\Queries\Admin\GetUserQueryInterface;
use App\Queries\Admin\GetUsersQuery;
use App\Queries\Admin\GetUsersQueryInterface;
use Illuminate\Support\ServiceProvider;

final class AdminServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        GetDriversQueryInterface::class           => GetDriversQuery::class,
        GetUsersQueryInterface::class             => GetUsersQuery::class,
        GetUserQueryInterface::class              => GetUserQuery::class,
        GetRidesQueryInterface::class             => GetRidesQuery::class,
        GetRideQueryInterface::class              => GetRideQuery::class,
        GetTicketsQueryInterface::class           => GetTicketsQuery::class,
        GetDriverQueryInterface::class            => GetDriverQuery::class,
        GetPromoCodesQueryInterface::class        => GetPromoCodesQuery::class,
        GetAnalyticsOverviewQueryInterface::class => GetAnalyticsOverviewQuery::class,
        GetRidesAnalyticsQueryInterface::class    => GetRidesAnalyticsQuery::class,
        GetRevenueAnalyticsQueryInterface::class  => GetRevenueAnalyticsQuery::class,
        GetRealTimeAnalyticsQueryInterface::class => GetRealTimeAnalyticsQuery::class,
        ExportRidesQueryInterface::class          => ExportRidesQuery::class,
        RidesCsvExportPresenterInterface::class   => RidesCsvExportPresenter::class,
        DriverProfilePresenterInterface::class    => DriverProfilePresenter::class,
    ];
}
