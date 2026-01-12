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

    public function updated(User $user): void
    {
        $this->userCache->invalidateOnUserChange($user, 'avatar_paths');

        if ($user->isDirty(['first_name', 'last_name'])) {
            $this->userCache->invalidateOnUserChange($user, 'first_name');
        }
    }

    public function deleted(User $user): void
    {
        $this->userCache->forgetAvatarUrls($user);
        $this->userCache->forgetUserProfile($user->id);
    }
}
