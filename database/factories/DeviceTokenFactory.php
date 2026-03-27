<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DevicePlatform;
use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DeviceToken>
 */
final class DeviceTokenFactory extends Factory
{
    protected $model = DeviceToken::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'platform'     => DevicePlatform::IOS,
            'token'        => $this->faker->unique()->regexify('[a-zA-Z0-9]{150}'),
            'device_name'  => $this->faker->randomElement(['iPhone 15 Pro', 'Samsung Galaxy S24', 'Pixel 8']),
            'app_version'  => '1.2.0',
            'last_used_at' => now(),
        ];
    }
}
