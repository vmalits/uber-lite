<?php

declare(strict_types=1);

namespace App\Presenters\User;

use App\Data\User\ProfileResponse;
use App\Models\User;
use App\Services\Avatar\AvatarUrlService;
use App\Services\Driver\DriverLocationConfig;
use Illuminate\Contracts\Redis\Factory as RedisFactory;

final readonly class UserProfilePresenter implements UserProfilePresenterInterface
{
    public function __construct(
        private AvatarUrlService $avatarUrlService,
        private RedisFactory $redis,
        private DriverLocationConfig $driverLocationConfig,
    ) {}

    public function present(User $user): ProfileResponse
    {
        $isOnline = null;

        if ($user->isDriver()) {
            $state = $this->redis
                ->connection()
                ->get($this->driverLocationConfig->stateKey($user->id));
            $isOnline = $state === 'online';
        }

        return ProfileResponse::fromUser($user, $this->avatarUrlService, $isOnline);
    }
}
