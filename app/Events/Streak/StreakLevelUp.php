<?php

declare(strict_types=1);

namespace App\Events\Streak;

use App\Enums\StreakLevel;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class StreakLevelUp implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly int $currentStreak,
        public readonly StreakLevel $newLevel,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("user:{$this->user->id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'streak.level_up';
    }

    /**
     * @return array{current_streak: int, new_level: string, discount_percent: int}
     */
    public function broadcastWith(): array
    {
        return [
            'current_streak'   => $this->currentStreak,
            'new_level'        => $this->newLevel->value,
            'discount_percent' => $this->newLevel->discountPercent(),
        ];
    }
}
