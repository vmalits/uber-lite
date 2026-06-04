<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Models\BlockedDriver;
use App\Models\User;

final readonly class UnblockDriverAction
{
    public function handle(User $rider, BlockedDriver $blockedDriver): void
    {
        $blockedDriver->delete();
    }
}
