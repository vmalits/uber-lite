<?php

declare(strict_types=1);

namespace App\Data\Auth;

use App\Enums\ProfileStep;
use Spatie\LaravelData\Data;

final class VerifyOtpResponse extends Data
{
    public function __construct(
        public string $profile_step,
        public string $token,
        public string $token_type = 'Bearer',
    ) {}

    public static function of(ProfileStep $profileStep, string $token): self
    {
        return new self(
            profile_step: $profileStep->value,
            token: $token,
        );
    }
}
