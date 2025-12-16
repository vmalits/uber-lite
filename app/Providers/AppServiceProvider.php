<?php

declare(strict_types=1);

namespace App\Providers;

use App\Queries\Auth\FindUserByPhoneQuery;
use App\Queries\Auth\FindUserByPhoneQueryInterface;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->bind(FindUserByPhoneQueryInterface::class, FindUserByPhoneQuery::class);
        $this->app->bind(FindUserByPhoneQuery::class, FindUserByPhoneQuery::class);
    }

    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip(),
            );
        });
    }
}
