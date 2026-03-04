<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Enums\PaymentMethodType;
use App\Enums\PaymentProvider;
use App\Models\PaymentMethod;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class PaymentMethodData extends Data
{
    public function __construct(
        public string $id,
        public PaymentMethodType $type,
        public PaymentProvider $provider,
        public ?string $last_four,
        public ?string $card_brand,
        #[MapName('expiry_month')]
        public ?int $expiryMonth,
        #[MapName('expiry_year')]
        public ?int $expiryYear,
        #[MapName('holder_name')]
        public ?string $holderName,
        #[MapName('is_default')]
        public bool $isDefault,
        #[MapName('is_expired')]
        public bool $isExpired,
    ) {}

    public static function fromModel(PaymentMethod $paymentMethod): self
    {
        return new self(
            id: $paymentMethod->id,
            type: $paymentMethod->type,
            provider: $paymentMethod->provider,
            last_four: $paymentMethod->last_four,
            card_brand: $paymentMethod->card_brand,
            expiryMonth: $paymentMethod->expiry_month !== null ? (int) $paymentMethod->expiry_month : null,
            expiryYear: $paymentMethod->expiry_year !== null ? (int) $paymentMethod->expiry_year : null,
            holderName: $paymentMethod->holder_name,
            isDefault: $paymentMethod->is_default,
            isExpired: $paymentMethod->isExpired(),
        );
    }
}
