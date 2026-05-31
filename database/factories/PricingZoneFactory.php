<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PricingZone;
use Illuminate\Database\Eloquent\Factories\Factory;

class PricingZoneFactory extends Factory
{
    protected $model = PricingZone::class;

    public function definition(): array
    {
        return [
            'name'             => $this->faker->city(),
            'slug'             => $this->faker->unique()->slug(2),
            'is_enabled'       => true,
            'surge_multiplier' => $this->faker->randomFloat(2, 1.0, 2.0),
            'reason'           => $this->faker->optional()->randomElement(['high_demand_area', 'event_zone', 'airport_zone']),
            'center_lat'       => $this->faker->latitude(46.9, 47.1),
            'center_lng'       => $this->faker->longitude(28.7, 29.0),
            'radius_meters'    => $this->faker->numberBetween(500, 5000),
        ];
    }
}
