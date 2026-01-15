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
        $status = $this->faker->randomElement([
            RideStatus::PENDING,
            RideStatus::STARTED,
            RideStatus::COMPLETED,
        ]);

        $startedAt = $status === RideStatus::STARTED ? now() : null;
        $completedAt = $status === RideStatus::COMPLETED ? now() : null;

        return [
            'rider_id'              => User::factory(),
            'driver_id'             => null,
            'origin_address'        => $this->faker->address(),
            'origin_lat'            => $this->faker->latitude(),
            'origin_lng'            => $this->faker->longitude(),
            'destination_address'   => $this->faker->address(),
            'destination_lat'       => $this->faker->latitude(),
            'destination_lng'       => $this->faker->longitude(),
            'status'                => $status,
            'price'                 => $this->faker->numberBetween(50, 500),
            'started_at'            => $startedAt,
            'completed_at'          => $completedAt,
            'estimated_distance_km' => $this->faker->randomFloat(1, 5, 50),
            'base_fee'              => 25.0,
            'price_per_km'          => 6.5,
            'price_per_minute'      => 1.2,
            'estimated_price'       => $this->faker->numberBetween(50, 500),
        ];
    }
}
