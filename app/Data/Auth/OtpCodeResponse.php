<?php

declare(strict_types=1);

namespace App\Data\Auth;

use App\Models\OtpCode;
use Spatie\LaravelData\Data;

final class OtpCodeResponse extends Data
{
    public function __construct(
        public string $phone,
    ) {}

    public static function fromModel(OtpCode $otpCode): self
    {
        return new self(
            phone: $otpCode->phone,
        );
    }
}
