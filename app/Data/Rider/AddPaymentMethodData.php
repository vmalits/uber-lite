<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Enums\PaymentMethodType;
use App\Enums\PaymentProvider;
use App\Http\Requests\V1\Rider\AddPaymentMethodRequest;
use Spatie\LaravelData\Data;

final class AddPaymentMethodData extends Data
{
    public function __construct(
        public PaymentMethodType $type,
        public PaymentProvider $provider,
        public string $token,
        public string $last_four,
        public string $card_brand,
        public ?int $expiry_month = null,
        public ?int $expiry_year = null,
        public ?string $holder_name = null,
    ) {}

    public static function fromRequest(AddPaymentMethodRequest $request): self
    {
        return new self(
            type: PaymentMethodType::from($request->string('type')->toString()),
            provider: PaymentProvider::from($request->string('provider')->toString()),
            token: $request->string('token')->toString(),
            last_four: $request->string('last_four')->toString(),
            card_brand: $request->string('card_brand')->toString(),
            expiry_month: $request->integer('expiry_month') ?: null,
            expiry_year: $request->integer('expiry_year') ?: null,
            holder_name: $request->string('holder_name')->toString() ?: null,
        );
    }
}
