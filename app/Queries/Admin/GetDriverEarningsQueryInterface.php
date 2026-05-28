<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Data\Admin\AdminDriverEarningsData;
use App\Models\User;

interface GetDriverEarningsQueryInterface
{
    public function execute(User $driver, ?string $from = null, ?string $to = null): AdminDriverEarningsData;
}
