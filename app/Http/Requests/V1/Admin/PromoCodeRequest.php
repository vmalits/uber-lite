<?php

declare(strict_types=1);

namespace App\Http\Requests\V1\Admin;

use App\Enums\DiscountType;
use App\Models\PromoCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class PromoCodeRequest extends FormRequest
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
        /** @var PromoCode|null $promoCode */
        $promoCode = $this->route('promoCode');
        $promoCodeId = $promoCode?->id;

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('promo_codes', 'code')->ignore($promoCodeId),
            ],
            'title'                => ['required', 'string', 'max:255'],
            'description'          => ['nullable', 'string'],
            'discount_type'        => ['required', Rule::enum(DiscountType::class)],
            'discount_value'       => ['required', 'integer', 'min:1'],
            'max_discount_amount'  => ['nullable', 'integer', 'min:1'],
            'min_order_amount'     => ['nullable', 'integer', 'min:0'],
            'usage_limit'          => ['nullable', 'integer', 'min:1'],
            'usage_limit_per_user' => ['nullable', 'integer', 'min:1'],
            'starts_at'            => ['nullable', 'date'],
            'expires_at'           => ['nullable', 'date', 'after_or_equal:starts_at'],
            'is_active'            => ['boolean'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function bodyParameters(): array
    {
        return [
            'code' => [
                'description' => 'Unique promo code.',
                'example'     => 'SUMMER2024',
            ],
            'title' => [
                'description' => 'Display title for the promo code.',
                'example'     => 'Summer Discount',
            ],
            'description' => [
                'description' => 'Optional description.',
                'example'     => 'Get 20% off your summer rides!',
            ],
            'discount_type' => [
                'description' => 'Type of discount: fixed or percentage.',
                'example'     => 'percentage',
            ],
            'discount_value' => [
                'description' => 'Discount value (MDL for fixed, % for percentage).',
                'example'     => 20,
            ],
            'max_discount_amount' => [
                'description' => 'Maximum discount amount for percentage type.',
                'example'     => 100,
            ],
            'min_order_amount' => [
                'description' => 'Minimum order amount to apply promo.',
                'example'     => 50,
            ],
            'usage_limit' => [
                'description' => 'Total number of uses allowed.',
                'example'     => 1000,
            ],
            'usage_limit_per_user' => [
                'description' => 'Number of uses per user.',
                'example'     => 1,
            ],
            'starts_at' => [
                'description' => 'When the promo becomes active.',
                'example'     => '2024-06-01 00:00:00',
            ],
            'expires_at' => [
                'description' => 'When the promo expires.',
                'example'     => '2024-08-31 23:59:59',
            ],
            'is_active' => [
                'description' => 'Whether the promo code is active.',
                'example'     => true,
            ],
        ];
    }
}
