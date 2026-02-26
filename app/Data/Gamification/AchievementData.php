<?php

declare(strict_types=1);

namespace App\Data\Gamification;

use Spatie\LaravelData\Data;

final class AchievementData extends Data
{
    public function __construct(
        public string $id,
        public string $name,
        public string $key,
        public ?string $description,
        public ?string $icon,
        public string $category,
        public int $target_value,
        public int $points_reward,
    ) {}
}
