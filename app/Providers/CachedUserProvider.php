<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

final class CachedUserProvider extends EloquentUserProvider
{
    public function __construct(
        private readonly CacheRepository $cache,
        private readonly int $cacheTtl,
        $hasher,
        $model,
    ) {
        parent::__construct($hasher, $model);
    }

    /**
     * Retrieve a user by their unique identifier with caching.
     *
     * @param mixed $identifier
     */
    public function retrieveById($identifier): ?Authenticatable
    {
        // @phpstan-ignore binaryOp.invalid
        $cacheKey = 'user:'.$identifier;

        return $this->cache->remember(
            key: $cacheKey,
            ttl: $this->cacheTtl,
            callback: fn () => parent::retrieveById($identifier),
        );
    }
}
