<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use Illuminate\Foundation\Http\FormRequest;

final class CreateWalletTopUpRequest extends FormRequest
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
            'amount'            => ['required', 'integer', 'min:50', 'max:50000'],
            'payment_method_id' => ['nullable', 'string'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'amount' => [
                'description' => 'Amount to top up in minor units (bani for MDL). Min: 50, Max: 50000.',
                'example'     => 5000,
            ],
            'payment_method_id' => [
                'description' => 'Optional payment method ID. If not provided, the default payment method will be used.',
                'example'     => '01j234567890abcdefghijk',
            ],
        ];
    }

    public function getAmount(): int
    {
        return $this->integer('amount');
    }

    public function getPaymentMethodId(): ?string
    {
        $value = $this->input('payment_method_id');

        return \is_string($value) ? $value : null;
    }
}
