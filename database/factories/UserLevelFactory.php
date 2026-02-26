<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserTier;
use App\Models\User;
use App\Models\UserLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserLevelFactory extends Factory
{
    protected $model = UserLevel::class;

    public function definition(): array
    {
        $xp = fake()->numberBetween(0, 10000);

        return [
            'user_id' => User::factory(),
            'level'   => (int) floor($xp / 100) + 1,
            'xp'      => $xp,
            'tier'    => UserTier::fromXp($xp),
        ];
    }

    public function bronze(): static
    {
        return $this->state([
            'xp'    => fake()->numberBetween(0, 499),
            'level' => fake()->numberBetween(1, 5),
            'tier'  => UserTier::BRONZE,
        ]);
    }

    public function silver(): static
    {
        return $this->state([
            'xp'    => fake()->numberBetween(500, 1999),
            'level' => fake()->numberBetween(5, 20),
            'tier'  => UserTier::SILVER,
        ]);
    }

    public function gold(): static
    {
        return $this->state([
            'xp'    => fake()->numberBetween(2000, 4999),
            'level' => fake()->numberBetween(20, 50),
            'tier'  => UserTier::GOLD,
        ]);
    }

    public function platinum(): static
    {
        return $this->state([
            'xp'    => fake()->numberBetween(5000, 20000),
            'level' => fake()->numberBetween(50, 200),
            'tier'  => UserTier::PLATINUM,
        ]);
    }
}
