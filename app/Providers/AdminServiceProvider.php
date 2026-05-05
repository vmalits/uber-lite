<?php

declare(strict_types=1);

namespace App\Providers;

use App\Presenters\Admin\DriverProfilePresenter;
use App\Presenters\Admin\DriverProfilePresenterInterface;
use App\Presenters\Admin\RidesCsvExportPresenter;
use App\Presenters\Admin\RidesCsvExportPresenterInterface;
use App\Queries\Admin\ExportRidesQuery;
use App\Queries\Admin\ExportRidesQueryInterface;
use App\Queries\Admin\GetAchievementsQuery;
use App\Queries\Admin\GetAchievementsQueryInterface;
use App\Queries\Admin\GetAnalyticsOverviewQuery;
use App\Queries\Admin\GetAnalyticsOverviewQueryInterface;
use App\Queries\Admin\GetAnnouncementsQuery;
use App\Queries\Admin\GetAnnouncementsQueryInterface;
use App\Queries\Admin\GetDriverQuery;
use App\Queries\Admin\GetDriverQueryInterface;
use App\Queries\Admin\GetDriverRidesQuery;
use App\Queries\Admin\GetDriverRidesQueryInterface;
use App\Queries\Admin\GetRideQuery;
use App\Queries\Admin\GetRideQueryInterface;
use App\Queries\Admin\GetRidesAnalyticsQuery;
use App\Queries\Admin\GetRidesAnalyticsQueryInterface;
use App\Queries\Admin\GetRidesQuery;
use App\Queries\Admin\GetRidesQueryInterface;
use App\Queries\Admin\GetTicketsQuery;
use App\Queries\Admin\GetTicketsQueryInterface;
use App\Queries\Admin\GetUserCreditTransactionsQuery;
use App\Queries\Admin\GetUserCreditTransactionsQueryInterface;
use App\Queries\Admin\GetUserQuery;
use App\Queries\Admin\GetUserQueryInterface;
use App\Queries\Admin\GetUsersQuery;
use App\Queries\Admin\GetUsersQueryInterface;
use App\Queries\Announcement\GetActiveAnnouncementsQuery;
use App\Queries\Announcement\GetActiveAnnouncementsQueryInterface;
use App\Queries\Report\GetUserReportQuery;
use App\Queries\Report\GetUserReportQueryInterface;
use App\Queries\Report\GetUserReportsQuery;
use App\Queries\Report\GetUserReportsQueryInterface;
use Illuminate\Support\ServiceProvider;

final class AdminServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        GetDriversQueryInterface::class                => GetDriversQuery::class,
        GetUsersQueryInterface::class                  => GetUsersQuery::class,
        GetUserQueryInterface::class                   => GetUserQuery::class,
        GetRidesQueryInterface::class                  => GetRidesQuery::class,
        GetRideQueryInterface::class                   => GetRideQuery::class,
        GetTicketsQueryInterface::class                => GetTicketsQuery::class,
        GetDriverQueryInterface::class                 => GetDriverQuery::class,
        GetDriverRidesQueryInterface::class            => GetDriverRidesQuery::class,
        GetPromoCodesQueryInterface::class             => GetPromoCodesQuery::class,
        GetAchievementsQueryInterface::class           => GetAchievementsQuery::class,
        GetAnnouncementsQueryInterface::class          => GetAnnouncementsQuery::class,
        GetAnalyticsOverviewQueryInterface::class      => GetAnalyticsOverviewQuery::class,
        GetRidesAnalyticsQueryInterface::class         => GetRidesAnalyticsQuery::class,
        GetRevenueAnalyticsQueryInterface::class       => GetRevenueAnalyticsQuery::class,
        GetRealTimeAnalyticsQueryInterface::class      => GetRealTimeAnalyticsQuery::class,
        ExportRidesQueryInterface::class               => ExportRidesQuery::class,
        RidesCsvExportPresenterInterface::class        => RidesCsvExportPresenter::class,
        DriverProfilePresenterInterface::class         => DriverProfilePresenter::class,
        GetPayoutQueryInterface::class                 => GetPayoutQuery::class,
        GetActiveAnnouncementsQueryInterface::class    => GetActiveAnnouncementsQuery::class,
        GetReportsQueryInterface::class                => GetReportsQuery::class,
        GetReportQueryInterface::class                 => GetReportQuery::class,
        GetUserReportsQueryInterface::class            => GetUserReportsQuery::class,
        GetUserReportQueryInterface::class             => GetUserReportQuery::class,
        GetUserCreditTransactionsQueryInterface::class => GetUserCreditTransactionsQuery::class,
    ];
}
