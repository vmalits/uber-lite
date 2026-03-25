<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\DriverSchedule;
use App\Models\User;

final class DriverSchedulePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::DRIVER;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::DRIVER;
    }

    public function view(User $user, DriverSchedule $schedule): bool
    {
        return $user->role === UserRole::DRIVER && $schedule->driver_id === $user->id;
    }

    public function update(User $user, DriverSchedule $schedule): bool
    {
        return $user->role === UserRole::DRIVER && $schedule->driver_id === $user->id;
    }

    public function delete(User $user, DriverSchedule $schedule): bool
    {
        return $user->role === UserRole::DRIVER && $schedule->driver_id === $user->id;
    }
}
