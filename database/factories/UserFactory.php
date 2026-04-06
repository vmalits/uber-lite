<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Locale;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone'             => '+3736'.fake()->numberBetween(1000000, 9999999),
            'email'             => null,
            'email_verified_at' => null,
            'phone_verified_at' => null,
            'first_name'        => null,
            'last_name'         => null,
            'last_login_at'     => null,
            'role'              => null,
            'locale'            => 'en',
            'profile_step'      => null,
            'status'            => UserStatus::ACTIVE,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role'         => UserRole::ADMIN,
            'profile_step' => ProfileStep::COMPLETED,
            'first_name'   => fake()->firstNameMale(),
            'last_name'    => fake()->lastName(),
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }

    public function rider(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role'              => UserRole::RIDER,
            'first_name'        => fake()->firstName(),
            'last_name'         => fake()->lastName(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'profile_step'      => ProfileStep::COMPLETED,
            'locale'            => fake()->randomElement(Locale::cases()),
            'referral_code'     => strtoupper(substr(md5(fake()->uuid()), 0, 8)),
            'credits_balance'   => fake()->numberBetween(0, 5000),
            'last_login_at'     => now()->subDays(fake()->numberBetween(0, 7)),
        ]);
    }

    public function driver(): static
    {
        return $this->state(fn (array $attributes): array => [
            'role'              => UserRole::DRIVER,
            'first_name'        => fake()->firstNameMale(),
            'last_name'         => fake()->lastName(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'profile_step'      => ProfileStep::COMPLETED,
            'locale'            => fake()->randomElement(Locale::cases()),
            'referral_code'     => strtoupper(substr(md5(fake()->uuid()), 0, 8)),
            'credits_balance'   => fake()->numberBetween(0, 2000),
            'driver_balance'    => fake()->numberBetween(5000, 80000),
            'last_login_at'     => now()->subDays(fake()->numberBetween(0, 3)),
        ]);
    }

    public function banned(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status'    => UserStatus::BANNED,
            'banned_at' => now()->subDays(fake()->numberBetween(1, 30)),
        ]);
    }
}
