<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Model;

final class CachedUserProvider extends EloquentUserProvider
{
    public function __construct(
        private readonly CacheRepository $cache,
        private readonly int $cacheTtl,
        Hasher $hasher,
        string $model,
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
        /** @var string|int $identifier */
        $id = (string) $identifier;
        $cacheKey = 'user:'.$id;

        /** @var (Authenticatable&Model)|null */
        return $this->cache->remember(
            key: $cacheKey,
            ttl: $this->cacheTtl,
            callback: function () use ($identifier): ?Authenticatable {
                return parent::retrieveById($identifier);
            },
        );
    }
}
