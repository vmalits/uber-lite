<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Ride;
use App\Models\RideMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RideMessage>
 */
final class RideMessageFactory extends Factory
{
    protected $model = RideMessage::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ride_id'   => Ride::factory(),
            'sender_id' => User::factory(),
            'message'   => $this->faker->sentence(),
            'read_at'   => null,
        ];
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'read_at' => now(),
        ]);
    }
}
