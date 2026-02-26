<?php

declare(strict_types=1);

namespace App\Events\Gamification;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class AchievementUnlocked implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly Achievement $achievement,
        public readonly string $tier,
        public readonly int $totalXp,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("user:{$this->user->id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'achievement.unlocked';
    }

    /**
     * @return array{
     *     achievement_id: string,
     *     name: string,
     *     points: int,
     *     tier: string,
     *     total_xp: int
     * }
     */
    public function broadcastWith(): array
    {
        return [
            'achievement_id' => $this->achievement->id,
            'name'           => $this->achievement->name,
            'points'         => $this->achievement->points_reward,
            'tier'           => $this->tier,
            'total_xp'       => $this->totalXp,
        ];
    }
}
