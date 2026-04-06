<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\BanSource;
use App\Enums\DevicePlatform;
use App\Enums\DriverAvailabilityStatus;
use App\Enums\DriverBanType;
use App\Enums\UserTier;
use App\Models\DeviceToken;
use App\Models\DriverBan;
use App\Models\DriverLocation;
use App\Models\DriverSchedule;
use App\Models\EmergencyContact;
use App\Models\User;
use App\Models\UserLevel;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

final class DriverSeeder extends Seeder
{
    private const array VEHICLES = [
        [
            'brand' => 'Volkswagen',
            'model' => 'Golf',
            'year'  => 2020,
            'color' => 'black',
            'type'  => 'sedan',
            'seats' => 5,
        ],
        [
            'brand' => 'Skoda',
            'model' => 'Octavia',
            'year'  => 2021,
            'color' => 'white',
            'type'  => 'sedan',
            'seats' => 5,
        ],
        [
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'year'  => 2019,
            'color' => 'silver',
            'type'  => 'sedan',
            'seats' => 5,
        ],
        [
            'brand' => 'Hyundai',
            'model' => 'Accent',
            'year'  => 2022,
            'color' => 'blue',
            'type'  => 'sedan',
            'seats' => 5,
        ],
        [
            'brand' => 'Kia',
            'model' => 'Ceed',
            'year'  => 2021,
            'color' => 'red',
            'type'  => 'hatchback',
            'seats' => 5,
        ],
        [
            'brand' => 'Renault',
            'model' => 'Logan',
            'year'  => 2018,
            'color' => 'gray',
            'type'  => 'sedan',
            'seats' => 5,
        ],
        [
            'brand' => 'Dacia',
            'model' => 'Duster',
            'year'  => 2022,
            'color' => 'green',
            'type'  => 'suv',
            'seats' => 5,
        ],
        [
            'brand' => 'BMW',
            'model' => '3 Series',
            'year'  => 2020,
            'color' => 'black',
            'type'  => 'sedan',
            'seats' => 5,
        ],
        [
            'brand' => 'Mercedes',
            'model' => 'C-Class',
            'year'  => 2019,
            'color' => 'white',
            'type'  => 'sedan',
            'seats' => 5,
        ],
        [
            'brand' => 'Ford',
            'model' => 'Focus',
            'year'  => 2020,
            'color' => 'blue',
            'type'  => 'hatchback',
            'seats' => 5,
        ],
        [
            'brand' => 'Opel',
            'model' => 'Astra',
            'year'  => 2021,
            'color' => 'silver',
            'type'  => 'hatchback',
            'seats' => 5,
        ],
        [
            'brand' => 'Nissan',
            'model' => 'Qashqai',
            'year'  => 2022,
            'color' => 'gray',
            'type'  => 'suv',
            'seats' => 5,
        ],
        [
            'brand' => 'Chevrolet',
            'model' => 'Cruze',
            'year'  => 2019,
            'color' => 'white',
            'type'  => 'sedan',
            'seats' => 5,
        ],
        [
            'brand' => 'Peugeot',
            'model' => '308',
            'year'  => 2020,
            'color' => 'red',
            'type'  => 'hatchback',
            'seats' => 5,
        ],
        [
            'brand' => 'Toyota',
            'model' => 'RAV4',
            'year'  => 2023,
            'color' => 'black',
            'type'  => 'suv',
            'seats' => 5,
        ],
    ];

    public function run(): void
    {
        $drivers = User::factory(15)
            ->driver()
            ->verified()
            ->create();

        /** @var User|null $admin */
        $admin = User::where('email', 'admin@uber-lite.md')->first();

        foreach ($drivers as $index => $driver) {
            $vehicleData = self::VEHICLES[$index];

            Vehicle::query()->create([
                'driver_id'    => $driver->id,
                'brand'        => $vehicleData['brand'],
                'model'        => $vehicleData['model'],
                'year'         => $vehicleData['year'],
                'color'        => $vehicleData['color'],
                'plate_number' => 'C '.strtoupper(fake()->bothify('??####')),
                'vehicle_type' => $vehicleData['type'],
                'seats'        => $vehicleData['seats'],
            ]);

            DriverLocation::query()->create([
                'driver_id'      => $driver->id,
                'status'         => $index < 10 ? DriverAvailabilityStatus::ONLINE : DriverAvailabilityStatus::OFFLINE,
                'lat'            => fake()->latitude(46.95, 47.08),
                'lng'            => fake()->longitude(28.75, 28.95),
                'last_active_at' => now()->subMinutes($index < 10 ? fake()->numberBetween(0,
                    25) : fake()->numberBetween(60, 1440)),
            ]);

            foreach (range(1, 5) as $day) {
                DriverSchedule::query()->create([
                    'driver_id'   => $driver->id,
                    'day_of_week' => $day,
                    'start_time'  => '08:00',
                    'end_time'    => '18:00',
                    'enabled'     => true,
                ]);
            }

            DriverSchedule::query()->create([
                'driver_id'   => $driver->id,
                'day_of_week' => 6,
                'start_time'  => '10:00',
                'end_time'    => '15:00',
                'enabled'     => fake()->boolean(40),
            ]);

            $xp = fake()->numberBetween(0, 8000);
            UserLevel::query()->create([
                'user_id' => $driver->id,
                'level'   => (int) floor($xp / 100) + 1,
                'xp'      => $xp,
                'tier'    => UserTier::fromXp($xp),
            ]);

            DeviceToken::query()->create([
                'user_id'      => $driver->id,
                'platform'     => fake()->randomElement([DevicePlatform::IOS, DevicePlatform::ANDROID]),
                'token'        => fake()->unique()->regexify('[a-zA-Z0-9]{150}'),
                'device_name'  => fake()->randomElement(['iPhone 15 Pro', 'Samsung Galaxy S24', 'Pixel 8', 'iPhone 14']),
                'app_version'  => '1.2.0',
                'last_used_at' => now()->subHours(fake()->numberBetween(0, 48)),
            ]);

            EmergencyContact::factory()->primary()->create(['user_id' => $driver->id]);
            EmergencyContact::factory()->create(['user_id' => $driver->id]);
        }

        if ($admin !== null) {
            DriverBan::query()->create([
                'driver_id'  => $drivers[11]->id,
                'banned_by'  => $admin->id,
                'ban_source' => BanSource::ADMIN,
                'ban_type'   => DriverBanType::TEMPORARY,
                'reason'     => 'Multiple rider complaints about unsafe driving',
                'expires_at' => now()->addDays(7),
            ]);

            DriverBan::query()->create([
                'driver_id'    => $drivers[13]->id,
                'banned_by'    => $admin->id,
                'ban_source'   => BanSource::ADMIN,
                'ban_type'     => DriverBanType::TEMPORARY,
                'reason'       => 'Cancelled too many rides',
                'expires_at'   => now()->subDays(3),
                'unbanned_at'  => now()->subDays(2),
                'unbanned_by'  => $admin->id,
                'unban_reason' => 'Ban expired, driver apologized',
            ]);
        }
    }
}
