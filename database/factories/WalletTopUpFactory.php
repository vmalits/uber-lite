<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Currency;
use App\Enums\WalletTopUpStatus;
use App\Models\User;
use App\Models\WalletTopUp;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WalletTopUp>
 */
final class WalletTopUpFactory extends Factory
{
    protected $model = WalletTopUp::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'           => User::factory(),
            'amount'            => fake()->numberBetween(5000, 5000000),
            'currency'          => Currency::MDL,
            'payment_method_id' => null,
            'payment_intent_id' => 'pi_'.fake()->unique()->uuid(),
            'status'            => WalletTopUpStatus::PENDING,
            'failure_reason'    => null,
            'completed_at'      => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'       => WalletTopUpStatus::COMPLETED,
            'completed_at' => now(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'         => WalletTopUpStatus::CANCELLED,
            'failure_reason' => 'Cancelled by user',
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status'         => WalletTopUpStatus::FAILED,
            'failure_reason' => 'Payment failed',
        ]);
    }
}
