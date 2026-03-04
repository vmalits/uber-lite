<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PaymentMethodType;
use App\Enums\PaymentProvider;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentMethod>
 */
final class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'        => User::factory(),
            'type'           => PaymentMethodType::CARD,
            'provider'       => PaymentProvider::STRIPE,
            'provider_token' => 'pm_' . fake()->uuid(),
            'last_four'      => fake()->numerify('####'),
            'card_brand'     => fake()->randomElement(['visa', 'mastercard', 'amex']),
            'expiry_month'   => fake()->numberBetween(1, 12),
            'expiry_year'    => now()->year + fake()->numberBetween(1, 5),
            'holder_name'    => fake()->name(),
            'is_default'     => false,
        ];
    }

    public function default(): self
    {
        return $this->state([
            'is_default' => true,
        ]);
    }

    public function applePay(): self
    {
        return $this->state([
            'type'           => PaymentMethodType::APPLE_PAY,
            'provider_token' => null,
            'last_four'      => null,
            'card_brand'     => null,
            'expiry_month'   => null,
            'expiry_year'    => null,
            'holder_name'    => null,
        ]);
    }

    public function googlePay(): self
    {
        return $this->state([
            'type'           => PaymentMethodType::GOOGLE_PAY,
            'provider_token' => null,
            'last_four'      => null,
            'card_brand'     => null,
            'expiry_month'   => null,
            'expiry_year'    => null,
            'holder_name'    => null,
        ]);
    }

    public function expired(): self
    {
        return $this->state([
            'expiry_month' => 1,
            'expiry_year'  => now()->year - 1,
        ]);
    }
}
