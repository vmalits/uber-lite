<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Models\Ride;
use Spatie\LaravelData\Data;

/**
 * @param float|null $discount_amount
 * @param float|null $estimated_price
 * @param float $final_price
 */
final class AppliedPromoCodeData extends Data
{
    public function __construct(
        public ?float $discount_amount,
        public ?float $estimated_price,
        public float $final_price,
    ) {}

    public static function fromRide(Ride $ride): self
    {
        return new self(
            discount_amount: $ride->discount_amount,
            estimated_price: $ride->estimated_price,
            final_price: max(0, ($ride->estimated_price ?? 0) - ($ride->discount_amount ?? 0)),
        );
    }
}
