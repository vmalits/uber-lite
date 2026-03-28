<?php

declare(strict_types=1);

namespace App\Data\Admin;

use App\Enums\AchievementCategory;
use Spatie\LaravelData\Data;

/**
 * @param array<string, mixed>|null $metadata
 */
final class CreateAchievementData extends Data
{
    /**
     * @param array<string, mixed>|null $metadata
     */
    public function __construct(
        public string $name,
        public string $key,
        public ?string $description,
        public ?string $icon,
        public AchievementCategory $category,
        public int $target_value,
        public int $points_reward,
        public ?array $metadata,
        public bool $is_active,
    ) {}
}
