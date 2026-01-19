<?php

declare(strict_types=1);

namespace App\Cache;

use App\Models\User;
use Closure;
use Illuminate\Cache\Repository;

final readonly class UserCache
{
    public function __construct(
        private Repository $cache,
    ) {}

    private function tag(User $user): string
    {
        return "user:{$user->id}";
    }

    /**
     * @template TCacheValue
     *
     * @param Closure():TCacheValue $resolver
     *
     * @return TCacheValue
     */
    public function rememberProfile(
        User $user,
        Closure $resolver,
        int $ttl = 3600,
    ): mixed {
        /** @var TCacheValue */
        return $this->cache->tags($this->tag($user))
            ->remember('profile', $ttl, $resolver);
    }

    public function invalidate(User $user): void
    {
        $this->cache->tags($this->tag($user))->flush();
    }
}
