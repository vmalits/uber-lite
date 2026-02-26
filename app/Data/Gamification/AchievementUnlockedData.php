<?php

declare(strict_types=1);

namespace App\Data\Gamification;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class AchievementUnlockedData extends Data
{
    public function __construct(
        #[MapName('achievement_id')]
        public string $achievementId,
        public string $name,
        public int $points,
        public string $tier,
        #[MapName('total_xp')]
        public int $totalXp,
    ) {}
}
