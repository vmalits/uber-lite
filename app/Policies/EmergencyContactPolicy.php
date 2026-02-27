<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\EmergencyContact;
use App\Models\User;

final class EmergencyContactPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, EmergencyContact $emergencyContact): bool
    {
        return $emergencyContact->user()->is($user);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, EmergencyContact $emergencyContact): bool
    {
        return $emergencyContact->user()->is($user);
    }

    public function delete(User $user, EmergencyContact $emergencyContact): bool
    {
        return $emergencyContact->user()->is($user);
    }
}
