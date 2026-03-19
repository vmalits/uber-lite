<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Currency;
use App\Enums\PaymentStatus;
use App\Models\PaymentAttempt;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentAttempt>
 */
final class PaymentAttemptFactory extends Factory
{
    protected $model = PaymentAttempt::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'                 => User::factory(),
            'ride_id'                 => Ride::factory(),
            'payment_method_id'       => null,
            'status'                  => PaymentStatus::PENDING,
            'amount'                  => fake()->numberBetween(5000, 50000),
            'credits_used'            => 0,
            'card_amount'             => 0,
            'currency'                => Currency::MDL,
            'provider'                => null,
            'provider_transaction_id' => null,
            'failure_reason'          => null,
            'metadata'                => null,
            'completed_at'            => null,
        ];
    }

    public function completed(?string $transactionId = null): self
    {
        return $this->state([
            'status'                  => PaymentStatus::COMPLETED,
            'completed_at'            => now(),
            'provider_transaction_id' => $transactionId ?? 'ch_'.fake()->uuid(),
        ]);
    }

    public function failed(string $reason = 'Charge declined.'): self
    {
        return $this->state([
            'status'         => PaymentStatus::FAILED,
            'failure_reason' => $reason,
            'completed_at'   => now(),
        ]);
    }

    public function processing(): self
    {
        return $this->state([
            'status' => PaymentStatus::PROCESSING,
        ]);
    }
}
