<?php

declare(strict_types=1);

namespace App\Providers;

use App\Queries\Driver\GetAvailableRidesQuery;
use App\Queries\Driver\GetAvailableRidesQueryInterface;
use Illuminate\Support\ServiceProvider;

final class DriverServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        GetAvailableRidesQueryInterface::class => GetAvailableRidesQuery::class,
        GetAvailableRidesQuery::class          => GetAvailableRidesQuery::class,
    ];
}
