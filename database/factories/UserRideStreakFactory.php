<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use App\Models\UserRideStreak;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<UserRideStreak>
 */
final class UserRideStreakFactory extends Factory
{
    protected $model = UserRideStreak::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'           => User::factory(),
            'current_streak'    => 0,
            'longest_streak'    => 0,
            'last_ride_date'    => null,
            'streak_started_at' => null,
        ];
    }

    public function withStreak(int $days): static
    {
        return $this->state(fn (array $attributes): array => [
            'current_streak'    => $days,
            'longest_streak'    => $days,
            'last_ride_date'    => now(),
            'streak_started_at' => now()->subDays($days),
        ]);
    }
}
