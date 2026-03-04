<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\PaymentMethod;
use App\Models\User;

final class PaymentMethodPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::RIDER;
    }

    public function view(User $user, PaymentMethod $paymentMethod): bool
    {
        return $paymentMethod->user()->is($user);
    }

    public function delete(User $user, PaymentMethod $paymentMethod): bool
    {
        return $paymentMethod->user()->is($user);
    }
}
