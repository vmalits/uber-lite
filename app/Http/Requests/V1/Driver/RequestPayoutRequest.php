<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Driver;

use App\Data\Driver\RequestPayoutData;
use App\Enums\PayoutMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class RequestPayoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'amount'                => ['required', 'integer', 'min:100', 'max:10000000'],
            'method'                => ['required', 'string', Rule::in(PayoutMethod::values())],
            'bank_name'             => ['required_if:method,bank_transfer', 'string', 'max:100'],
            'bank_account_number'   => ['required_if:method,bank_transfer', 'string', 'max:50'],
            'bank_routing_number'   => ['required_if:method,bank_transfer', 'string', 'max:20'],
            'crypto_wallet_address' => ['required_if:method,crypto_wallet', 'string', 'max:255'],
            'crypto_currency'       => ['required_if:method,crypto_wallet', 'string', 'max:10'],
            'description'           => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'amount' => [
                'description' => 'Payout amount in cents (min: 100, max: 10,000,000).',
                'example'     => 50000,
                'type'        => 'integer',
                'required'    => true,
            ],
            'method' => [
                'description' => 'Payout method (bank_transfer or crypto_wallet).',
                'example'     => 'bank_transfer',
                'type'        => 'string',
                'required'    => true,
            ],
            'bank_name' => [
                'description' => 'Bank name (required if method is bank_transfer).',
                'example'     => 'First National Bank',
                'type'        => 'string',
                'required'    => false,
            ],
            'bank_account_number' => [
                'description' => 'Bank account number (required if method is bank_transfer).',
                'example'     => '1234567890123456',
                'type'        => 'string',
                'required'    => false,
            ],
            'bank_routing_number' => [
                'description' => 'Bank routing number (required if method is bank_transfer).',
                'example'     => '021000021',
                'type'        => 'string',
                'required'    => false,
            ],
            'crypto_wallet_address' => [
                'description' => 'Crypto wallet address (required if method is crypto_wallet).',
                'example'     => '0x742d35Cc6634C0532925a3b844Bc9e7595f2bD5',
                'type'        => 'string',
                'required'    => false,
            ],
            'crypto_currency' => [
                'description' => 'Cryptocurrency code (required if method is crypto_wallet).',
                'example'     => 'ETH',
                'type'        => 'string',
                'required'    => false,
            ],
            'description' => [
                'description' => 'Optional description for the payout request.',
                'example'     => 'Weekly earnings payout',
                'type'        => 'string',
                'required'    => false,
            ],
        ];
    }

    public function toData(): RequestPayoutData
    {
        /** @var array{amount: int|string, method: string, bank_name?: string, bank_account_number?: string, bank_routing_number?: string, crypto_wallet_address?: string, crypto_currency?: string, description?: string} $validated */
        $validated = $this->validated();

        return new RequestPayoutData(
            amount: (int) $validated['amount'],
            method: PayoutMethod::from($validated['method']),
            bankName: $validated['bank_name'] ?? null,
            bankAccountNumber: $validated['bank_account_number'] ?? null,
            bankRoutingNumber: $validated['bank_routing_number'] ?? null,
            cryptoWalletAddress: $validated['crypto_wallet_address'] ?? null,
            cryptoCurrency: $validated['crypto_currency'] ?? null,
            description: $validated['description'] ?? null,
        );
    }
}
