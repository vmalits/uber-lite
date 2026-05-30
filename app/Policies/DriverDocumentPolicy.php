<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\DriverDocument;
use App\Models\User;

final class DriverDocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::DRIVER;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::DRIVER;
    }

    public function view(User $user, DriverDocument $document): bool
    {
        return $user->role === UserRole::DRIVER && $document->driver_id === $user->id;
    }

    public function delete(User $user, DriverDocument $document): bool
    {
        return $user->role === UserRole::DRIVER && $document->driver_id === $user->id;
    }
}
