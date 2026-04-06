<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RideFactory extends Factory
{
    public const array CHISINAU_LOCATIONS = [
        ['address' => 'Strada Ștefan cel Mare 1, Centru', 'lat' => 47.0268, 'lng' => 28.8416],
        ['address' => 'Bulevardul Ștefan cel Mare și Sfânt 75, Centru', 'lat' => 47.0305, 'lng' => 28.8361],
        ['address' => 'Strada Tighina 49, Botanica', 'lat' => 46.9986, 'lng' => 28.8574],
        ['address' => 'Bulevardul Decebel 21, Botanica', 'lat' => 46.9935, 'lng' => 28.8623],
        ['address' => 'Strada Alexandru cel Bun 52, Buiucani', 'lat' => 47.0367, 'lng' => 28.8156],
        ['address' => 'Strada Ion Creangă 16, Buiucani', 'lat' => 47.0382, 'lng' => 28.8089],
        ['address' => 'Strada Albișoara 4, Rîșcani', 'lat' => 47.0451, 'lng' => 28.8305],
        ['address' => 'Bulevardul Constantin Negruzzi 7, Rîșcani', 'lat' => 47.0489, 'lng' => 28.8247],
        ['address' => 'Strada Armenească 25, Centru', 'lat' => 47.0243, 'lng' => 28.8489],
        ['address' => 'Strada Banulescu Bodoni 30, Centru', 'lat' => 47.0221, 'lng' => 28.8356],
        ['address' => 'Strada 31 August 1989 131, Telecentru', 'lat' => 47.0145, 'lng' => 28.8423],
        ['address' => 'Strada Mihail Kogălniceanu 62, Telecentru', 'lat' => 47.0178, 'lng' => 28.8367],
        ['address' => 'Aeroportul Internațional Chișinău', 'lat' => 46.9277, 'lng' => 28.9313],
        ['address' => 'Strada Aeroportului 80, Revaca', 'lat' => 46.9345, 'lng' => 28.9178],
        ['address' => 'Malldova, Strada Arborilor 21', 'lat' => 47.0321, 'lng' => 28.8123],
        ['address' => 'Shopping Malldova, Tudor Vladimirescu', 'lat' => 47.0335, 'lng' => 28.8098],
        ['address' => 'UN Shopping Center, Strada Petro Zadnipru', 'lat' => 47.0198, 'lng' => 28.8534],
        ['address' => 'Gara Feroviară Chișinău, Strada Garii 1', 'lat' => 47.0112, 'lng' => 28.8605],
        ['address' => 'Catedrala Națională, Piața Marii Adunări Naționale', 'lat' => 47.0256, 'lng' => 28.8327],
        ['address' => 'Arcul de Triumf, Piața Marii Adunări Naționale', 'lat' => 47.0261, 'lng' => 28.8312],
        ['address' => 'Strada Mitropolit Gheorghe 6, Centru', 'lat' => 47.0278, 'lng' => 28.8278],
        ['address' => 'Bulevardul Grigore Vieru 10, Centru', 'lat' => 47.0295, 'lng' => 28.8234],
        ['address' => 'Strada Ismail 53, Botanica', 'lat' => 47.0012, 'lng' => 28.8498],
        ['address' => 'Strada Cuza Vodă 44, Centru', 'lat' => 47.0234, 'lng' => 28.8445],
    ];

    public function definition(): array
    {
        $origin = fake()->randomElement(self::CHISINAU_LOCATIONS);
        $destination = fake()->randomElement(self::CHISINAU_LOCATIONS);
        while ($origin['address'] === $destination['address']) {
            $destination = fake()->randomElement(self::CHISINAU_LOCATIONS);
        }

        return [
            'rider_id'               => User::factory()->rider(),
            'driver_id'              => null,
            'origin_address'         => $origin['address'],
            'origin_lat'             => $origin['lat'],
            'origin_lng'             => $origin['lng'],
            'destination_address'    => $destination['address'],
            'destination_lat'        => $destination['lat'],
            'destination_lng'        => $destination['lng'],
            'status'                 => RideStatus::PENDING,
            'price'                  => null,
            'base_fee'               => 25.0,
            'price_per_km'           => 6.5,
            'price_per_minute'       => 1.2,
            'estimated_price'        => fake()->numberBetween(50, 300),
            'estimated_distance_km'  => fake()->randomFloat(1, 2, 15),
            'estimated_duration_min' => fake()->randomFloat(1, 5, 30),
        ];
    }

    public function completed(): static
    {
        return $this->state(function (array $attributes): array {
            $price = $attributes['estimated_price'] ?? fake()->numberBetween(50, 300);
            $completedAt = now()->subDays(fake()->numberBetween(0, 30));

            return [
                'status'       => RideStatus::COMPLETED,
                'price'        => $price,
                'arrived_at'   => $completedAt->copy()->subMinutes(fake()->numberBetween(10, 40)),
                'started_at'   => $completedAt->copy()->subMinutes(fake()->numberBetween(8, 35)),
                'completed_at' => $completedAt,
                'created_at'   => $completedAt->copy()->subMinutes(fake()->numberBetween(15, 50)),
                'updated_at'   => $completedAt,
            ];
        });
    }

    public function cancelled(): static
    {
        return $this->state(function (array $attributes): array {
            $cancelledAt = now()->subDays(fake()->numberBetween(0, 14));

            return [
                'status'            => RideStatus::CANCELLED,
                'cancelled_at'      => $cancelledAt,
                'cancelled_by_type' => fake()->randomElement(ActorType::cases()),
                'cancelled_reason'  => fake()->randomElement([
                    'rider_cancelled',
                    'driver_cancelled',
                    'no_drivers_available',
                    'rider_no_show',
                ]),
                'created_at' => $cancelledAt->copy()->subMinutes(fake()->numberBetween(2, 15)),
                'updated_at' => $cancelledAt,
            ];
        });
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status'       => RideStatus::SCHEDULED,
            'scheduled_at' => now()->addDays(fake()->numberBetween(1, 5)),
            'created_at'   => now()->subHours(fake()->numberBetween(1, 12)),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => RideStatus::PENDING,
        ]);
    }

    public function active(): static
    {
        return $this->state(function (array $attributes): array {
            $activeStatus = fake()->randomElement([
                RideStatus::ACCEPTED,
                RideStatus::ON_THE_WAY,
                RideStatus::ARRIVED,
                RideStatus::STARTED,
            ]);
            $data = [
                'status'     => $activeStatus,
                'created_at' => now()->subMinutes(fake()->numberBetween(3, 20)),
                'updated_at' => now(),
            ];
            if (\in_array(
                $activeStatus, [
                    RideStatus::ON_THE_WAY,
                    RideStatus::ARRIVED,
                    RideStatus::STARTED,
                ], true)
            ) {
                $data['arrived_at'] = $activeStatus === RideStatus::ARRIVED || $activeStatus === RideStatus::STARTED
                    ? now()->subMinutes(fake()->numberBetween(1, 5))
                    : null;
            }
            if ($activeStatus === RideStatus::STARTED) {
                $data['started_at'] = now()->subMinutes(fake()->numberBetween(1, 10));
            }

            return $data;
        });
    }

    public function withDriver(User $driver): static
    {
        return $this->state(fn (array $attributes): array => [
            'driver_id' => $driver->id,
        ]);
    }

    public function forRider(User $rider): static
    {
        return $this->state(fn (array $attributes): array => [
            'rider_id' => $rider->id,
        ]);
    }
}
