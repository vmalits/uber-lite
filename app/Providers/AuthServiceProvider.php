<?php

declare(strict_types=1);

namespace App\Providers;

use App\Queries\Auth\FindUserByPhoneQuery;
use App\Queries\Auth\FindUserByPhoneQueryInterface;
use App\Queries\Auth\FindValidOtpCodeByPhoneQuery;
use App\Queries\Auth\FindValidOtpCodeByPhoneQueryInterface;
use Illuminate\Support\ServiceProvider;

final class AuthServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        FindUserByPhoneQueryInterface::class         => FindUserByPhoneQuery::class,
        FindValidOtpCodeByPhoneQueryInterface::class => FindValidOtpCodeByPhoneQuery::class,
    ];
}
