<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Vehicle;

final class VehiclePolicy
{
    public function create(User $user): bool
    {
        return $user->role === UserRole::DRIVER;
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        return $user->role === UserRole::DRIVER && $vehicle->driver_id === $user->id;
    }
}
