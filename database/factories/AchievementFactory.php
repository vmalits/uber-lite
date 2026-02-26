<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AchievementCategory;
use App\Models\Achievement;
use Illuminate\Database\Eloquent\Factories\Factory;

class AchievementFactory extends Factory
{
    protected $model = Achievement::class;

    public function definition(): array
    {
        return [
            'name'          => fake()->words(3, true),
            'key'           => fake()->unique()->slug(2),
            'description'   => fake()->sentence(),
            'icon'          => fake()->randomElement(['trophy', 'star', 'medal', 'badge', 'crown']),
            'category'      => fake()->randomElement(AchievementCategory::cases()),
            'target_value'  => fake()->numberBetween(1, 100),
            'points_reward' => fake()->numberBetween(10, 100),
            'metadata'      => null,
            'is_active'     => true,
        ];
    }

    public function driver(): static
    {
        return $this->state(['category' => AchievementCategory::DRIVER]);
    }

    public function rider(): static
    {
        return $this->state(['category' => AchievementCategory::RIDER]);
    }

    public function common(): static
    {
        return $this->state(['category' => AchievementCategory::COMMON]);
    }
}
