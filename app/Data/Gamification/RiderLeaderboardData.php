<?php

declare(strict_types=1);

namespace App\Data\Gamification;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class RiderLeaderboardData extends Data
{
    public function __construct(
        public string $id,
        public string $first_name,
        public string $last_name,
        /** @var array<string, string>|null */
        public ?array $avatar_paths,
        #[MapName('current_streak')]
        public int $currentStreak,
        #[MapName('total_rides')]
        public int $totalRides,
        public int $rank,
    ) {}
}
