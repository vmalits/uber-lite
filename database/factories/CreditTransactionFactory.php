<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CreditTransactionType;
use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CreditTransaction>
 */
final class CreditTransactionFactory extends Factory
{
    protected $model = CreditTransaction::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = fake()->numberBetween(-5000, 5000);

        return [
            'user_id'       => User::factory(),
            'amount'        => $amount,
            'balance_after' => fake()->numberBetween(0, 100000),
            'type'          => fake()->randomElement(CreditTransactionType::cases()),
            'description'   => fake()->sentence(),
            'related_id'    => null,
        ];
    }

    public function referralBonus(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => fake()->numberBetween(100, 1000),
            'type'   => CreditTransactionType::REFERRAL_BONUS,
        ]);
    }

    public function promoSaving(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => fake()->numberBetween(50, 500),
            'type'   => CreditTransactionType::PROMO_SAVING,
        ]);
    }

    public function ridePayment(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => fake()->numberBetween(-5000, -100),
            'type'   => CreditTransactionType::RIDE_PAYMENT,
        ]);
    }

    public function adminAdjustment(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => fake()->numberBetween(-10000, 10000),
            'type'   => CreditTransactionType::ADMIN_ADJUSTMENT,
        ]);
    }
}
