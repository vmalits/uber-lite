<?php

declare(strict_types=1);

namespace App\Presenters\User;

use App\Cache\UserCache;
use App\Data\User\ProfileResponse;
use App\Models\User;
use App\Services\AvatarStorageService;

final readonly class UserProfilePresenter implements UserProfilePresenterInterface
{
    public function __construct(
        private AvatarStorageService $avatarStorage,
        private UserCache $userCache,
    ) {}

    public function present(User $user): ProfileResponse
    {
        /** @var ProfileResponse */
        return $this->userCache->rememberProfile(
            user: $user,
            resolver: function () use ($user): ProfileResponse {
                $user->refresh();
                $avatarPath = $user->avatar_path ?? null;
                $avatarUrl = $avatarPath !== null
                    ? $this->avatarStorage->url($avatarPath)
                    : null;

                return ProfileResponse::fromUser(
                    $user,
                    $avatarUrl,
                );
            },
        );
    }
}
