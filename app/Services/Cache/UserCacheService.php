<?php

declare(strict_types=1);

namespace App\Services\Cache;

use App\Data\User\ProfileResponse;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Cache\Repository;

/**
 * @template TCacheValue
 */
final class UserCacheService
{
    private const int AVATAR_CACHE_TTL = 86400;

    private const int PROFILE_CACHE_TTL = 3600;

    public function __construct(
        private readonly Repository $cache,
    ) {}

    /**
     * @template T of mixed
     *
     * @param Closure(): T $callback
     *
     * @return T
     */
    public function remember(string $key, int $ttl, Closure $callback): mixed
    {
        /** @var T $result */
        $result = $this->cache->remember($key, $ttl, $callback);

        return $result;
    }

    public function forget(string $key): bool
    {
        return $this->cache->forget($key);
    }

    /**
     * @param Closure(): ?string $callback
     */
    public function rememberAvatarUrl(User $user, string $size, Closure $callback): ?string
    {
        $key = $this->avatarUrlKey($user->id, $size);
        /** @var ?string $result */
        $result = $this->cache->remember($key, self::AVATAR_CACHE_TTL, $callback);

        return $result;
    }

    public function forgetAvatarUrls(User $user): void
    {
        foreach (['sm', 'md', 'lg'] as $size) {
            $this->cache->forget($this->avatarUrlKey($user->id, $size));
        }
    }

    /**
     * @param Closure(): ProfileResponse $callback
     */
    public function rememberUserProfile(int|string $userId, Closure $callback): ProfileResponse
    {
        $key = $this->userProfileKey($userId);

        return $this->cache->remember($key, self::PROFILE_CACHE_TTL, $callback);
    }

    public function forgetUserProfile(int|string $userId): void
    {
        $this->cache->forget($this->userProfileKey($userId));
    }

    public function invalidateOnUserChange(User $user, string $field): void
    {
        if (\in_array($field, ['first_name', 'last_name', 'avatar_paths'])) {
            $this->forgetAvatarUrls($user);
            $this->forgetUserProfile($user->id);
        }
    }

    private function avatarUrlKey(int|string $userId, string $size): string
    {
        return "avatar:{$userId}:{$size}";
    }

    private function userProfileKey(int|string $userId): string
    {
        return "user:{$userId}:profile";
    }
}
