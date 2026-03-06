<?php

declare(strict_types=1);

namespace App\Queries\Streak;

use App\Data\Streak\StreakHistoryResponseData;
use App\Models\User;

interface GetStreakHistoryQueryInterface
{
    public function execute(User $user, int $days): StreakHistoryResponseData;
}
