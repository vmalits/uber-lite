<?php

declare(strict_types=1);

namespace Database\Factories;

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
            'profile_step'      => null,
        ];
    }

    /**
     * Indicate that the model's email address should be verified.
     */
    public function verified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }
}
