<?php

declare(strict_types=1);

namespace App\Events\Gamification;

use App\Enums\UserTier;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class LevelUp implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly UserTier $oldTier,
        public readonly UserTier $newTier,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("user:{$this->user->id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'level.up';
    }

    /**
     * @return array{
     *     old_tier: string,
     *     new_tier: string,
     *     level: int,
     *     xp: int
     * }
     */
    public function broadcastWith(): array
    {
        $userLevel = $this->user->level;

        return [
            'old_tier' => $this->oldTier->value,
            'new_tier' => $this->newTier->value,
            'level'    => $userLevel->level ?? 1,
            'xp'       => $userLevel->xp ?? 0,
        ];
    }
}
