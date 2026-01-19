<?php

declare(strict_types=1);

namespace App\Data\Admin;

use App\Data\User\UserData;
use Spatie\LaravelData\Data;

final class DriverProfileData extends Data
{
    public function __construct(
        public UserData $user,
        public DriverStatsData $stats,
    ) {}
}
