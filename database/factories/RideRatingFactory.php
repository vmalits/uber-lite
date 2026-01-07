<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Ride;
use App\Models\RideRating;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RideRating>
 */
class RideRatingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ride_id'  => Ride::factory(),
            'rider_id' => User::factory(),
            'rating'   => $this->faker->numberBetween(1, 5),
            'comment'  => $this->faker->optional(0.7)->sentence(),
        ];
    }

    public function forRide(Ride $ride): self
    {
        return $this->state(fn (array $attributes) => [
            'ride_id'  => $ride->id,
            'rider_id' => $ride->rider_id,
        ]);
    }
}
