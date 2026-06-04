<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\BlockedDriver;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

final class BlockedDriverFactory extends Factory
{
    protected $model = BlockedDriver::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rider_id'  => User::factory()->state(['role' => UserRole::RIDER]),
            'driver_id' => User::factory()->state(['role' => UserRole::DRIVER]),
        ];
    }
}
