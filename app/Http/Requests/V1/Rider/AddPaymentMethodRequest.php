<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Rider;

use Illuminate\Foundation\Http\FormRequest;

final class AddPaymentMethodRequest extends FormRequest
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
            'type'         => ['required', 'string', 'in:card,apple_pay,google_pay'],
            'provider'     => ['required', 'string', 'in:stripe,paypal'],
            'token'        => ['required', 'string', 'max:255'],
            'last_four'    => ['required', 'string', 'size:4'],
            'card_brand'   => ['required', 'string', 'max:50'],
            'expiry_month' => ['nullable', 'integer', 'min:1', 'max:12'],
            'expiry_year'  => ['nullable', 'integer', 'min:'.now()->year, 'max:'.(now()->year + 20)],
            'holder_name'  => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, array<string, int|string>>
     */
    public function bodyParameters(): array
    {
        return [
            'type' => [
                'description' => 'Payment method type.',
                'example'     => 'card',
            ],
            'provider' => [
                'description' => 'Payment provider.',
                'example'     => 'stripe',
            ],
            'token' => [
                'description' => 'Provider token from client SDK.',
                'example'     => 'pm_1AbCdEfGhIjKlMnOpQrStUvWx',
            ],
            'last_four' => [
                'description' => 'Last four digits of the card.',
                'example'     => '4242',
            ],
            'card_brand' => [
                'description' => 'Card brand (visa, mastercard, etc.).',
                'example'     => 'visa',
            ],
            'expiry_month' => [
                'description' => 'Card expiry month (1-12).',
                'example'     => 12,
            ],
            'expiry_year' => [
                'description' => 'Card expiry year.',
                'example'     => 2027,
            ],
            'holder_name' => [
                'description' => 'Card holder name.',
                'example'     => 'John Doe',
            ],
        ];
    }
}
