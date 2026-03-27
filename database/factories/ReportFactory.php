<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
final class ReportFactory extends Factory
{
    protected $model = Report::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reporter_id' => User::factory(),
            'target_id'   => User::factory(),
            'ride_id'     => null,
            'reason'      => ReportReason::INAPPROPRIATE_BEHAVIOR,
            'description' => $this->faker->optional()->sentence(10),
            'status'      => ReportStatus::PENDING,
        ];
    }

    public function withRide(): static
    {
        return $this->state(fn (array $attributes) => [
            'ride_id' => Ride::factory(),
        ]);
    }

    public function resolved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'      => ReportStatus::RESOLVED,
            'admin_note'  => 'Action taken.',
            'resolved_by' => User::factory()->admin(),
            'resolved_at' => now(),
        ]);
    }
}
