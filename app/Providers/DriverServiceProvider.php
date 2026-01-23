<?php

declare(strict_types=1);

namespace App\Providers;

use App\Queries\Driver\GetActiveBanQuery;
use App\Queries\Driver\GetActiveBanQueryInterface;
use App\Queries\Driver\GetActiveBansQuery;
use App\Queries\Driver\GetActiveBansQueryInterface;
use App\Queries\Driver\GetActiveRideQuery;
use App\Queries\Driver\GetActiveRideQueryInterface;
use App\Queries\Driver\GetAvailableRidesQuery;
use App\Queries\Driver\GetAvailableRidesQueryInterface;
use App\Queries\Driver\GetVehiclesQuery;
use App\Queries\Driver\GetVehiclesQueryInterface;
use Illuminate\Support\ServiceProvider;

final class DriverServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        GetAvailableRidesQueryInterface::class => GetAvailableRidesQuery::class,
        GetActiveRideQueryInterface::class     => GetActiveRideQuery::class,
        GetActiveBanQueryInterface::class      => GetActiveBanQuery::class,
        GetActiveBansQueryInterface::class     => GetActiveBansQuery::class,
        GetVehiclesQueryInterface::class       => GetVehiclesQuery::class,
    ];
}
