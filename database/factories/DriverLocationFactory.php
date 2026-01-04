<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DriverLocationFactory extends Factory
{
    public function definition(): array
    {
        $lat = $this->faker->latitude(47.0, 48.5);
        $lng = $this->faker->longitude(27.0, 29.5);

        return [
            'lat'        => $lat,
            'lng'        => $lng,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
