<?php

declare(strict_types=1);

namespace App\Listeners\Streak;

use App\Actions\Streak\UpdateRideStreakAction;
use App\Enums\UserRole;
use App\Events\Rider\RideStatusChanged;
use App\Models\User;

final readonly class UpdateRideStreakListener
{
    public function __construct(
        private UpdateRideStreakAction $updateRideStreak,
    ) {}

    public function handle(RideStatusChanged $event): void
    {
        if ($event->to->value !== 'completed') {
            return;
        }

        $ride = $event->ride;

        /** @var User|null $rider */
        $rider = $ride->rider;

        if ($rider === null || $rider->role !== UserRole::RIDER) {
            return;
        }

        $completedAt = $ride->completed_at ?? now();

        $this->updateRideStreak->handle($rider, $completedAt);
    }
}
