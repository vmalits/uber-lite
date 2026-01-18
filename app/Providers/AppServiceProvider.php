<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Cache\UserCacheService;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureCommands();
        $this->configureModels();
        $this->configureUrl();
        $this->configureRateLimiting();
        $this->configureDate();
        $this->configureRoutePatterns();

        $this->app->bind(UserCacheService::class, function () {
            return new UserCacheService(cache: Cache::driver());
        });
    }

    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands($this->app->isProduction());
    }

    private function configureModels(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());
    }

    private function configureUrl(): void
    {
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip(),
            );
        });
    }

    private function configureDate(): void
    {
        Date::use(CarbonImmutable::class);
    }

    private function configureRoutePatterns(): void
    {
        Route::pattern('ulid', '[0-9a-fA-F]{26}');
    }
}
