<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Report;
use App\Models\User;

final class ReportPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Report $report): bool
    {
        return $user->isAdmin();
    }

    public function resolve(User $user, Report $report): bool
    {
        return $user->isAdmin();
    }
}
