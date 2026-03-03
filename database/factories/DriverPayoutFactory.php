<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PayoutMethod;
use App\Enums\PayoutStatus;
use App\Models\DriverPayout;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DriverPayout>
 */
final class DriverPayoutFactory extends Factory
{
    protected $model = DriverPayout::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $method = fake()->randomElement([PayoutMethod::BANK_TRANSFER, PayoutMethod::CRYPTO_WALLET]);

        $data = [
            'driver_id'    => User::factory(),
            'amount'       => fake()->numberBetween(1000, 100000),
            'status'       => PayoutStatus::PENDING,
            'method'       => $method,
            'requested_at' => now(),
        ];

        if ($method === PayoutMethod::BANK_TRANSFER) {
            $data['bank_name'] = fake()->company();
            $data['bank_account_number'] = fake()->numerify('####################');
            $data['bank_routing_number'] = fake()->numerify('#########');
        } else {
            $data['crypto_wallet_address'] = '0x'.fake()->regexify('[a-fA-F0-9]{40}');
            $data['crypto_currency'] = fake()->randomElement(['BTC', 'ETH', 'USDT']);
        }

        return $data;
    }
}
