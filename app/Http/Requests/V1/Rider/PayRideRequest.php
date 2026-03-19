<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use Illuminate\Foundation\Http\FormRequest;

final class PayRideRequest extends FormRequest
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
            'payment_method_id' => ['required', 'ulid', 'exists:payment_methods,id'],
            'credits_to_use'    => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array<string, array<string, int|string>>
     */
    public function bodyParameters(): array
    {
        return [
            'payment_method_id' => [
                'description' => 'ULID of the payment method to charge.',
                'example'     => '01JKABCDEF0123456789012345',
            ],
            'credits_to_use' => [
                'description' => 'Amount of credits to apply. Deducted from ride total before card charge.',
                'example'     => 500,
            ],
        ];
    }
}
