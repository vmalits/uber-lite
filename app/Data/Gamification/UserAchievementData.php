<?php

declare(strict_types=1);

namespace App\Data\Gamification;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class UserAchievementData extends Data
{
    public function __construct(
        public string $id,
        public AchievementData $achievement,
        public int $progress,
        #[MapName('progress_percentage')]
        public float $progressPercentage,
        #[MapName('completed_at')]
        public ?string $completedAt,
        #[MapName('is_completed')]
        public bool $isCompleted,
    ) {}
}
