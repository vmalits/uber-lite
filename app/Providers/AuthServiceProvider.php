<?php

declare(strict_types=1);

namespace App\Providers;

use App\Queries\Auth\FindUserByPhoneQuery;
use App\Queries\Auth\FindUserByPhoneQueryInterface;
use Illuminate\Support\ServiceProvider;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        FindUserByPhoneQueryInterface::class => FindUserByPhoneQuery::class,
        FindUserByPhoneQuery::class          => FindUserByPhoneQuery::class,
    ];
}
