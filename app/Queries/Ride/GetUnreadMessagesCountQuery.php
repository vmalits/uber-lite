<?php

declare(strict_types=1);

namespace App\Queries\Ride;

use App\Models\Ride;
use App\Models\User;

final readonly class GetUnreadMessagesCountQuery implements GetUnreadMessagesCountQueryInterface
{
    public function execute(Ride $ride, User $user): int
    {
        return $ride->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->count();
    }
}
