<?php

declare(strict_types=1);

namespace App\Data\Admin;

use App\Data\DateData;
use App\Enums\DiscountType;
use App\Models\PromoCode;
use Spatie\LaravelData\Data;

/**
 * @param string $id
 * @param string $code
 * @param string $title
 * @param string|null $description
 * @param DiscountType $discount_type
 * @param int $discount_value
 * @param int|null $max_discount_amount
 * @param int $min_order_amount
 * @param int|null $usage_limit
 * @param int $usage_limit_per_user
 * @param int $used_count
 * @param DateData|null $starts_at
 * @param DateData|null $expires_at
 * @param bool $is_active
 * @param DateData $created_at
 * @param DateData $updated_at
 */
final class PromoCodeData extends Data
{
    public function __construct(
        public string $id,
        public string $code,
        public string $title,
        public ?string $description,
        public DiscountType $discount_type,
        public int $discount_value,
        public ?int $max_discount_amount,
        public int $min_order_amount,
        public ?int $usage_limit,
        public int $usage_limit_per_user,
        public int $used_count,
        public ?DateData $starts_at,
        public ?DateData $expires_at,
        public bool $is_active,
        public DateData $created_at,
        public DateData $updated_at,
    ) {}

    public static function fromModel(PromoCode $promoCode): self
    {
        return new self(
            id: $promoCode->id,
            code: $promoCode->code,
            title: $promoCode->title,
            description: $promoCode->description,
            discount_type: $promoCode->discount_type,
            discount_value: $promoCode->discount_value,
            max_discount_amount: $promoCode->max_discount_amount,
            min_order_amount: $promoCode->min_order_amount,
            usage_limit: $promoCode->usage_limit,
            usage_limit_per_user: $promoCode->usage_limit_per_user,
            used_count: $promoCode->used_count,
            starts_at: $promoCode->starts_at ? DateData::fromCarbon($promoCode->starts_at) : null,
            expires_at: $promoCode->expires_at ? DateData::fromCarbon($promoCode->expires_at) : null,
            is_active: $promoCode->is_active,
            created_at: DateData::fromCarbon($promoCode->created_at),
            updated_at: DateData::fromCarbon($promoCode->updated_at),
        );
    }
}
