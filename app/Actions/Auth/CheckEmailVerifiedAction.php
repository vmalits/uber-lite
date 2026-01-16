<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Enums\ProfileStep;
use App\Exceptions\Auth\ProfileNotVerifiedException;
use App\Models\User;

final readonly class CheckEmailVerifiedAction
{
    public function validate(User $user): void
    {
        if (! $this->isVerified($user)) {
            throw new ProfileNotVerifiedException('Profile email is not verified.');
        }
    }

    private function isVerified(User $user): bool
    {
        return $user->hasVerifiedEmail()
            || $user->profile_step === ProfileStep::EMAIL_VERIFIED
            || $user->profile_step === ProfileStep::COMPLETED;
    }
}
