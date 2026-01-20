<?php

declare(strict_types=1);

namespace App\Presenters\User;

use App\Data\User\ProfileResponse;
use App\Models\User;
use App\Services\Avatar\AvatarUrlService;

final readonly class UserProfilePresenter implements UserProfilePresenterInterface
{
    public function __construct(
        private AvatarUrlService $avatarUrlService,
    ) {}

    public function present(User $user): ProfileResponse
    {
        return ProfileResponse::fromUser($user, $this->avatarUrlService);
    }
}
