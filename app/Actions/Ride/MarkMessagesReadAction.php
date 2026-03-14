<?php

declare(strict_types=1);

namespace App\Actions\Ride;

use App\Models\Ride;
use App\Models\User;

final readonly class MarkMessagesReadAction
{
    public function handle(User $user, Ride $ride): int
    {
        return $ride->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
