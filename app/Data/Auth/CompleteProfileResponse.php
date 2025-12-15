<?php

declare(strict_types=1);

namespace App\Data\Auth;

use App\Enums\ProfileStep;
use Spatie\LaravelData\Data;

final class CompleteProfileResponse extends Data
{
    public function __construct(
        public string $phone,
        public string $first_name,
        public string $last_name,
        public string $profile_step,
    ) {}

    public static function of(string $phone, string $firstName, string $lastName, ProfileStep|string $profileStep): self
    {
        $step = $profileStep instanceof ProfileStep ? $profileStep->value : (string) $profileStep;

        return new self(
            phone: $phone,
            first_name: $firstName,
            last_name: $lastName,
            profile_step: $step,
        );
    }
}
