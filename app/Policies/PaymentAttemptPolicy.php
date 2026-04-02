<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PaymentAttempt;
use App\Models\User;

final class PaymentAttemptPolicy
{
    public function refund(User $user, PaymentAttempt $paymentAttempt): bool
    {
        return $user->isAdmin();
    }
}
