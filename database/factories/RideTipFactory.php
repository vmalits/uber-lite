<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Ride;
use App\Models\RideTip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RideTip>
 */
final class RideTipFactory extends Factory
{
    protected $model = RideTip::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ride_id'   => Ride::factory(),
            'rider_id'  => User::factory(),
            'driver_id' => User::factory(),
            'amount'    => fake()->numberBetween(100, 5000),
            'comment'   => fake()->optional()->sentence(),
        ];
    }
}
