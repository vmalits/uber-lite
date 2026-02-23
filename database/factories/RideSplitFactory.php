<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Ride;
use App\Models\RideSplit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RideSplit>
 */
class RideSplitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ride_id'           => Ride::factory(),
            'participant_name'  => $this->faker->name(),
            'participant_email' => $this->faker->optional(0.8)->email(),
            'participant_phone' => $this->faker->optional(0.5)->phoneNumber(),
            'share'             => $this->faker->optional(0.7)->randomFloat(2, 10, 50),
        ];
    }

    public function forRide(Ride $ride): self
    {
        return $this->state(fn (array $attributes) => [
            'ride_id' => $ride->id,
        ]);
    }
}
