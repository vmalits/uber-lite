<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\FavoriteLocation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

final class FavoriteLocationFactory extends Factory
{
    protected $model = FavoriteLocation::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name'    => fake()->words(3, true),
            'lat'     => fake()->latitude(),
            'lng'     => fake()->longitude(),
            'address' => fake()->address(),
        ];
    }
}
