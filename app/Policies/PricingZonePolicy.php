<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PricingZone;
use App\Models\User;

final class PricingZonePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, PricingZone $zone): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, PricingZone $zone): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, PricingZone $zone): bool
    {
        return $user->isAdmin();
    }
}
