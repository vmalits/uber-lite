<?php

declare(strict_types=1);

namespace App\Presenters\User;

use App\Data\User\ProfileResponse;
use App\Models\User;
use App\Services\Avatar\AvatarUrlService;
use App\Services\Cache\UserCacheService;

final readonly class UserProfilePresenter implements UserProfilePresenterInterface
{
    /**
     * @param UserCacheService<ProfileResponse> $userCache
     */
    public function __construct(
        private AvatarUrlService $avatarUrlService,
        private UserCacheService $userCache,
    ) {}

    public function present(User $user): ProfileResponse
    {
        return $this->userCache->rememberUserProfile(
            userId: $user->id,
            callback: fn () => ProfileResponse::fromUser($user, $this->avatarUrlService),
        );
    }
}
