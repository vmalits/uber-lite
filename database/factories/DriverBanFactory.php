<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BanSource;
use App\Enums\DriverBanType;
use App\Enums\UserRole;
use App\Models\DriverBan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriverBanFactory extends Factory
{
    protected $model = DriverBan::class;

    public function definition(): array
    {
        $banType = $this->faker->randomElement(DriverBanType::cases());
        $banSource = $this->faker->randomElement(BanSource::cases());
        $isUnbanned = $this->faker->boolean(20);

        return [
            'driver_id'   => User::factory(),
            'banned_by'   => $banSource === UserRole::ADMIN ? User::factory() : null,
            'ban_source'  => $banSource,
            'ban_type'    => $banType,
            'reason'      => $this->faker->sentence(),
            'external_id' => $banSource !== UserRole::ADMIN ? $this->faker->uuid() : null,
            'expires_at'  => $banType === DriverBanType::TEMPORARY ? $this->faker->dateTimeBetween(
                '+1 day', '+1 month',
            ) : null,
            'unbanned_at' => $isUnbanned ? $this->faker->dateTimeBetween(
                '+2 days', '+2 months',
            ) : null,
            'unbanned_by'  => $isUnbanned ? User::factory() : null,
            'unban_reason' => $isUnbanned ? $this->faker->sentence() : null,
        ];
    }
}
