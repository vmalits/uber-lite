<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PromoCode;
use App\Models\User;

final class PromoCodePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, PromoCode $promoCode): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, PromoCode $promoCode): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, PromoCode $promoCode): bool
    {
        return $user->isAdmin();
    }
}
