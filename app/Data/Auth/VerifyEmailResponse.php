<?php

declare(strict_types=1);

namespace App\Data\Auth;

use App\Models\User;
use Spatie\LaravelData\Data;

final class VerifyEmailResponse extends Data
{
    public function __construct(
        public string $user_id,
        public ?string $email,
        public string $profile_step,
        public ?string $verified_at,
        public bool $verified,
        public bool $already_verified,
    ) {}

    public static function fromUser(User $user, bool $alreadyVerified = false): self
    {
        return new self(
            user_id: $user->id,
            email: $user->email,
            profile_step: $user->profile_step !== null ? $user->profile_step->value : '',
            verified_at: $user->email_verified_at?->toAtomString(),
            verified: $user->hasVerifiedEmail(),
            already_verified: $alreadyVerified,
        );
    }
}
