<?php

declare(strict_types=1);

namespace App\Events\Streak;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class StreakUpdated implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly int $currentStreak,
        public readonly int $previousStreak,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("user:{$this->user->id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'streak.updated';
    }

    /**
     * @return array{current_streak: int, previous_streak: int}
     */
    public function broadcastWith(): array
    {
        return [
            'current_streak'  => $this->currentStreak,
            'previous_streak' => $this->previousStreak,
        ];
    }
}
