<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\FavoriteRoute;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FavoriteRoute>
 */
class FavoriteRouteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'             => User::factory(),
            'name'                => $this->faker->randomElement(['Home-Work', 'Work-Home', 'Gym', 'Parents']),
            'origin_address'      => $this->faker->address(),
            'origin_lat'          => $this->faker->latitude(),
            'origin_lng'          => $this->faker->longitude(),
            'destination_address' => $this->faker->address(),
            'destination_lat'     => $this->faker->latitude(),
            'destination_lng'     => $this->faker->longitude(),
            'type'                => $this->faker->randomElement(['home', 'work', 'gym', 'other']),
        ];
    }

    public function forUser(User $user): self
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function type(string $type): self
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }
}
