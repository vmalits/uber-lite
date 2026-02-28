<?php

declare(strict_types=1);

namespace App\Events\Streak;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class StreakBroken
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly int $brokenStreak,
    ) {}
}
