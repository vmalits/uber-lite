<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\RideRating;
use App\Models\User;

final class RideRatingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::DRIVER;
    }

    public function view(User $user, RideRating $rating): bool
    {
        return $rating->ride->driver()->is($user);
    }
}
