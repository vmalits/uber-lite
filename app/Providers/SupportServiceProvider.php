<?php

declare(strict_types=1);

namespace App\Providers;

use App\Queries\Support\GetTicketsQuery;
use App\Queries\Support\GetTicketsQueryInterface;
use Illuminate\Support\ServiceProvider;

final class SupportServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        GetTicketsQueryInterface::class => GetTicketsQuery::class,
    ];
}
