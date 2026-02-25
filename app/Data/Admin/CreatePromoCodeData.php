<?php

declare(strict_types=1);

namespace App\Data\Admin;

use App\Enums\DiscountType;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

final class CreatePromoCodeData extends Data
{
    public function __construct(
        public string $code,
        public string $title,
        public ?string $description,
        public DiscountType $discount_type,
        public int $discount_value,
        public ?int $max_discount_amount,
        public int $min_order_amount,
        public ?int $usage_limit,
        public int $usage_limit_per_user,
        #[WithCast(DateTimeInterfaceCast::class, format: ['Y-m-d H:i:s', 'Y-m-d\TH:i:sP'])]
        public ?CarbonImmutable $starts_at,
        #[WithCast(DateTimeInterfaceCast::class, format: ['Y-m-d H:i:s', 'Y-m-d\TH:i:sP'])]
        public ?CarbonImmutable $expires_at,
        public bool $is_active,
    ) {}
}
