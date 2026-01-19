<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\VehicleType;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
final class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'driver_id'    => User::factory(),
            'brand'        => $this->faker->company(),
            'model'        => $this->faker->word(),
            'year'         => (int) $this->faker->year(),
            'color'        => $this->faker->safeColorName(),
            'plate_number' => strtoupper($this->faker->bothify('??####??')),
            'vehicle_type' => $this->faker->randomElement(VehicleType::cases())->value,
            'seats'        => $this->faker->numberBetween(1, 7),
        ];
    }
}
