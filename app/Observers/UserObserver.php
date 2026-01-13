<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;
use App\Services\Cache\UserCacheService;

final readonly class UserObserver
{
    /**
     * @param UserCacheService<string> $userCache
     */
    public function __construct(
        private UserCacheService $userCache,
    ) {}

    public function saved(User $user): void
    {
        $this->invalidateAuthCache($user);
    }

    public function updated(User $user): void
    {
        $this->invalidateAuthCache($user);

        if ($user->isDirty(['avatar_paths'])) {
            $this->userCache->forgetAvatarUrls($user);
            $this->userCache->forgetUserProfile($user->id);
        }

        if ($user->isDirty(['first_name', 'last_name'])) {
            $this->userCache->forgetUserProfile($user->id);
        }
    }

    public function deleted(User $user): void
    {
        $this->invalidateAuthCache($user);
        $this->userCache->forgetAvatarUrls($user);
        $this->userCache->forgetUserProfile($user->id);
    }

    private function invalidateAuthCache(User $user): void
    {
        cache()->forget("user:{$user->id}");
    }
}
