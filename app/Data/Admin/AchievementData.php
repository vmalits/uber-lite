<?php

declare(strict_types=1);

namespace App\Data\Admin;

use App\Data\DateData;
use App\Enums\AchievementCategory;
use App\Models\Achievement;
use Spatie\LaravelData\Data;

/**
 * @param string $id
 * @param string $name
 * @param string $key
 * @param string|null $description
 * @param string|null $icon
 * @param AchievementCategory $category
 * @param int $target_value
 * @param int $points_reward
 * @param array<string, mixed>|null $metadata
 * @param bool $is_active
 * @param DateData $created_at
 * @param DateData $updated_at
 */
final class AchievementData extends Data
{
    /**
     * @param array<string, mixed>|null $metadata
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $key,
        public ?string $description,
        public ?string $icon,
        public AchievementCategory $category,
        public int $target_value,
        public int $points_reward,
        public ?array $metadata,
        public bool $is_active,
        public DateData $created_at,
        public DateData $updated_at,
    ) {}

    public static function fromModel(Achievement $achievement): self
    {
        return new self(
            id: $achievement->id,
            name: $achievement->name,
            key: $achievement->key,
            description: $achievement->description,
            icon: $achievement->icon,
            category: $achievement->category,
            target_value: $achievement->target_value,
            points_reward: $achievement->points_reward,
            metadata: $achievement->metadata,
            is_active: $achievement->is_active,
            created_at: DateData::fromCarbon($achievement->created_at),
            updated_at: DateData::fromCarbon($achievement->updated_at),
        );
    }
}
