<?php

declare(strict_types=1);

namespace App\Providers;

use App\Queries\Auth\FindUserByPhoneQuery;
use App\Queries\Auth\FindUserByPhoneQueryInterface;
use App\Queries\Auth\FindValidOtpCodeByPhoneQuery;
use App\Queries\Auth\FindValidOtpCodeByPhoneQueryInterface;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Hashing\Hasher;
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

    public function boot(): void
    {
        auth()->provider('cached-eloquent', function (Application $app, array $config) {
            /** @var int $cacheTtl */
            $cacheTtl = $config['cache_ttl'] ?? 3600;
            /** @var string $model */
            $model = $config['model'];

            return new CachedUserProvider(
                cache: $app->make(CacheRepository::class),
                cacheTtl: $cacheTtl,
                hasher: $app->make(Hasher::class),
                model: $model,
            );
        });
    }
}
