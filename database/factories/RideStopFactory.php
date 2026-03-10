<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Ride;
use App\Models\RideStop;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RideStop>
 */
class RideStopFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ride_id' => Ride::factory(),
            'order'   => 1,
            'address' => $this->faker->streetAddress(),
            'lat'     => $this->faker->latitude(40.7, 40.8),
            'lng'     => $this->faker->longitude(-74.1, -73.9),
        ];
    }

    public function forRide(Ride $ride, int $order = 1): self
    {
        return $this->state(fn (array $attributes) => [
            'ride_id' => $ride->id,
            'order'   => $order,
        ]);
    }
}
