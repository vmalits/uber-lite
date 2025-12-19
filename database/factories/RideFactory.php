<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\RideStatus;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ride>
 */
class RideFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rider_id'            => User::factory(),
            'driver_id'           => null,
            'origin_address'      => $this->faker->address(),
            'origin_lat'          => $this->faker->latitude(),
            'origin_lng'          => $this->faker->longitude(),
            'destination_address' => $this->faker->address(),
            'destination_lat'     => $this->faker->latitude(),
            'destination_lng'     => $this->faker->longitude(),
            'status'              => RideStatus::PENDING,
            'price'               => $this->faker->randomFloat(2, 50, 500),
        ];
    }
}
