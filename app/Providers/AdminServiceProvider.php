<?php

declare(strict_types=1);

namespace App\Providers;

use App\Queries\Admin\GetRideQuery;
use App\Queries\Admin\GetRideQueryInterface;
use App\Queries\Admin\GetRidesQuery;
use App\Queries\Admin\GetRidesQueryInterface;
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
        GetUsersQueryInterface::class => GetUsersQuery::class,
        GetUserQueryInterface::class  => GetUserQuery::class,
        GetRidesQueryInterface::class => GetRidesQuery::class,
        GetRideQueryInterface::class  => GetRideQuery::class,
    ];
}
