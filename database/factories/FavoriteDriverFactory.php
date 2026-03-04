<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\FavoriteDriver;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

final class FavoriteDriverFactory extends Factory
{
    protected $model = FavoriteDriver::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'   => User::factory()->state(['role' => UserRole::RIDER]),
            'driver_id' => User::factory()->state(['role' => UserRole::DRIVER]),
        ];
    }
}
