<?php

declare(strict_types=1);

namespace App\Providers;

use App\Queries\Rider\GetActiveRideQuery;
use App\Queries\Rider\GetActiveRideQueryInterface;
use Illuminate\Support\ServiceProvider;

final class RiderServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        GetActiveRideQueryInterface::class => GetActiveRideQuery::class,
        GetActiveRideQuery::class          => GetActiveRideQuery::class,
    ];
}
