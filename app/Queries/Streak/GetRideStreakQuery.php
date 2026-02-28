<?php

declare(strict_types=1);

namespace App\Queries\Streak;

use App\Data\Streak\RideStreakData;
use App\Models\User;
use App\Models\UserRideStreak;

final readonly class GetRideStreakQuery
{
    public function execute(User $user): RideStreakData
    {
        $streak = UserRideStreak::query()
            ->where('user_id', $user->id)
            ->first();

        return RideStreakData::fromModel($streak);
    }
}
