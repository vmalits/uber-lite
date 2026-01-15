<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DriverAvailabilityStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DriverLocationFactory extends Factory
{
    public function definition(): array
    {
        $lat = $this->faker->latitude(47.0, 48.5);
        $lng = $this->faker->longitude(27.0, 29.5);

        return [
            'driver_id'      => User::factory(),
            'status'         => DriverAvailabilityStatus::OFFLINE,
            'lat'            => $lat,
            'lng'            => $lng,
            'location_point' => DB::raw("ST_SetSRID(ST_MakePoint($lng, $lat), 4326)"),
            'last_active_at' => Carbon::now(),
            'created_at'     => Carbon::now(),
            'updated_at'     => Carbon::now(),
        ];
    }

    public function online(): static
    {
        return $this->state(function (array $attributes): array {
            $lat = $attributes['lat'] ?? $this->faker->latitude(47.0, 48.5);
            $lng = $attributes['lng'] ?? $this->faker->longitude(27.0, 29.5);

            return [
                'status'         => DriverAvailabilityStatus::ONLINE,
                'lat'            => $lat,
                'lng'            => $lng,
                'location_point' => DB::raw("ST_SetSRID(ST_MakePoint($lng, $lat), 4326)"),
                'last_active_at' => Carbon::now(),
            ];
        });
    }

    public function offline(): static
    {
        return $this->state(function (array $attributes): array {
            return [
                'status'         => DriverAvailabilityStatus::OFFLINE,
                'lat'            => null,
                'lng'            => null,
                'location_point' => null,
            ];
        });
    }

    public function busy(): static
    {
        return $this->state(function (array $attributes): array {
            $lat = $attributes['lat'] ?? $this->faker->latitude(47.0, 48.5);
            $lng = $attributes['lng'] ?? $this->faker->longitude(27.0, 29.5);

            return [
                'status'         => DriverAvailabilityStatus::BUSY,
                'lat'            => $lat,
                'lng'            => $lng,
                'location_point' => DB::raw("ST_SetSRID(ST_MakePoint($lng, $lat), 4326)"),
            ];
        });
    }
}
