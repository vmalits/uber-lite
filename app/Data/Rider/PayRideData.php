<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Http\Requests\V1\Rider\PayRideRequest;
use Spatie\LaravelData\Data;

final class PayRideData extends Data
{
    public function __construct(
        public string $payment_method_id,
        public ?int $credits_to_use = null,
    ) {}

    public static function fromRequest(PayRideRequest $request): self
    {
        return new self(
            payment_method_id: $request->string('payment_method_id')->toString(),
            credits_to_use: $request->integer('credits_to_use') ?: null,
        );
    }
}
