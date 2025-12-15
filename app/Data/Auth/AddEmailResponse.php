<?php

declare(strict_types=1);

namespace App\Data\Auth;

use App\Enums\ProfileStep;
use Spatie\LaravelData\Data;

final class AddEmailResponse extends Data
{
    public function __construct(
        public string $phone,
        public string $email,
        public string $profile_step,
    ) {}

    public static function of(string $phone, string $email, ProfileStep|string $profileStep): self
    {
        $step = $profileStep instanceof ProfileStep ? $profileStep->value : (string) $profileStep;

        return new self(
            phone: $phone,
            email: $email,
            profile_step: $step,
        );
    }
}
