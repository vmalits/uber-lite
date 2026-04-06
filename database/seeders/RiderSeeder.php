<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\DevicePlatform;
use App\Enums\FavoriteRouteType;
use App\Enums\PaymentMethodType;
use App\Enums\PaymentProvider;
use App\Enums\UserTier;
use App\Models\DeviceToken;
use App\Models\EmergencyContact;
use App\Models\FavoriteDriver;
use App\Models\FavoriteLocation;
use App\Models\FavoriteRoute;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\UserLevel;
use App\Models\UserRideStreak;
use Illuminate\Database\Seeder;

final class RiderSeeder extends Seeder
{
    private const array FAVORITE_LOCATIONS = [
        [
            'name' => 'Acasă',
            'address' => 'Strada Ștefan cel Mare 1, Centru',
            'lat' => 47.0268,
            'lng' => 28.8416
        ],
        [
            'name' => 'Muncă',
            'address' => 'Bulevardul Decebal 21, Botanica',
            'lat' => 46.9935,
            'lng' => 28.8623
        ],
        [
            'name' => 'Mall',
            'address' => 'Malldova, Strada Arborilor 21',
            'lat' => 47.0321,
            'lng' => 28.8123
        ],
        [
            'name' => 'Gară',
            'address' => 'Gara Feroviară Chișinău, Strada Garii 1',
            'lat' => 47.0112,
            'lng' => 28.8605
        ],
        [
            'name' => 'Aeroport',
            'address' => 'Aeroportul Internațional Chișinău',
            'lat' => 46.9277,
            'lng' => 28.9313
        ],
        [
            'name' => 'Sala de sport',
            'address' => 'Strada 31 August 1989 131, Telecentru',
            'lat' => 47.0145,
            'lng' => 28.8423
        ],
        [
            'name' => 'Părinți',
            'address' => 'Strada Alexandru cel Bun 52, Buiucani',
            'lat' => 47.0367,
            'lng' => 28.8156
        ],
        [
            'name' => 'Parc',
            'address' => 'Catedrala Națională, Piața Marii Adunări Naționale',
            'lat' => 47.0256,
            'lng' => 28.8327
        ],
    ];

    public function run(): void
    {
        $riders = User::factory(25)
            ->rider()
            ->verified()
            ->create();

        $drivers = User::where('role', 'driver')->get();

        foreach ($riders as $index => $rider) {
            PaymentMethod::query()->create([
                'user_id' => $rider->id,
                'type' => PaymentMethodType::CARD,
                'provider' => PaymentProvider::STRIPE,
                'provider_token' => 'pm_'.fake()->uuid(),
                'last_four' => fake()->numerify('####'),
                'card_brand' => fake()->randomElement(['visa', 'mastercard']),
                'expiry_month' => fake()->numberBetween(1, 12),
                'expiry_year' => now()->year + fake()->numberBetween(1, 4),
                'holder_name' => $rider->first_name.' '.$rider->last_name,
                'is_default' => true,
            ]);

            if ($index % 3 === 0) {
                PaymentMethod::query()->create([
                    'user_id' => $rider->id,
                    'type' => PaymentMethodType::CARD,
                    'provider' => PaymentProvider::STRIPE,
                    'provider_token' => 'pm_'.fake()->uuid(),
                    'last_four' => fake()->numerify('####'),
                    'card_brand' => fake()->randomElement(['visa', 'mastercard', 'amex']),
                    'expiry_month' => fake()->numberBetween(1, 12),
                    'expiry_year' => now()->year + fake()->numberBetween(1, 3),
                    'holder_name' => $rider->first_name.' '.$rider->last_name,
                    'is_default' => false,
                ]);
            }

            $locationCount = fake()->numberBetween(2, 4);
            $locations = collect(self::FAVORITE_LOCATIONS)->random($locationCount);
            foreach ($locations as $location) {
                FavoriteLocation::query()->create([
                    'user_id' => $rider->id,
                    'name' => $location['name'],
                    'lat' => $location['lat'],
                    'lng' => $location['lng'],
                    'address' => $location['address'],
                ]);
            }

            if ($index % 2 === 0) {
                FavoriteRoute::query()->create([
                    'user_id' => $rider->id,
                    'name' => 'Acasă → Muncă',
                    'origin_address' => 'Strada Ștefan cel Mare 1, Centru',
                    'origin_lat' => 47.0268,
                    'origin_lng' => 28.8416,
                    'destination_address' => 'Bulevardul Decebal 21, Botanica',
                    'destination_lat' => 46.9935,
                    'destination_lng' => 28.8623,
                    'type' => FavoriteRouteType::WORK,
                ]);
            }

            if ($drivers->isNotEmpty() && fake()->boolean(40)) {
                FavoriteDriver::query()->create([
                    'user_id' => $rider->id,
                    'driver_id' => $drivers->random()->id,
                ]);
            }

            $xp = fake()->numberBetween(0, 6000);
            UserLevel::query()->create([
                'user_id' => $rider->id,
                'level' => (int) floor($xp / 100) + 1,
                'xp' => $xp,
                'tier' => UserTier::fromXp($xp),
            ]);

            $streak = fake()->numberBetween(0, 14);
            UserRideStreak::query()->create([
                'user_id' => $rider->id,
                'current_streak' => $streak,
                'longest_streak' => max($streak, fake()->numberBetween($streak, 20)),
                'last_ride_date' => $streak > 0 ? now()->subDays(fake()->numberBetween(0, 1)) : null,
                'streak_started_at' => $streak > 0 ? now()->subDays($streak) : null,
            ]);

            DeviceToken::query()->create([
                'user_id' => $rider->id,
                'platform' => fake()->randomElement([DevicePlatform::IOS, DevicePlatform::ANDROID]),
                'token' => fake()->unique()->regexify('[a-zA-Z0-9]{150}'),
                'device_name' => fake()->randomElement([
                    'iPhone 15', 'Samsung Galaxy A54', 'Pixel 7', 'iPhone 13', 'Xiaomi Redmi Note 12'
                ]),
                'app_version' => '1.2.0',
                'last_used_at' => now()->subHours(fake()->numberBetween(0, 72)),
            ]);

            EmergencyContact::factory()->primary()->create(['user_id' => $rider->id]);
            if (fake()->boolean(60)) {
                EmergencyContact::factory()->create(['user_id' => $rider->id]);
            }
        }
    }
}
