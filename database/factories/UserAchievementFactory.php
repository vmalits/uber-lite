<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Achievement;
use App\Models\User;
use App\Models\UserAchievement;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAchievementFactory extends Factory
{
    protected $model = UserAchievement::class;

    public function definition(): array
    {
        return [
            'user_id'        => User::factory(),
            'achievement_id' => Achievement::factory(),
            'progress'       => fake()->numberBetween(0, 50),
            'completed_at'   => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(function (array $attributes): array {
            $achievement = Achievement::find($attributes['achievement_id']);

            return [
                'progress'     => $achievement?->target_value ?? 100,
                'completed_at' => now(),
            ];
        });
    }
}
