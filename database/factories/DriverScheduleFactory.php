<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\DriverSchedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DriverSchedule>
 */
final class DriverScheduleFactory extends Factory
{
    protected $model = DriverSchedule::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'driver_id'   => User::factory(),
            'day_of_week' => $this->faker->numberBetween(0, 6),
            'start_time'  => '08:00',
            'end_time'    => '17:00',
            'enabled'     => true,
        ];
    }
}
