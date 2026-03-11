<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\DriverPayout;

interface GetPayoutQueryInterface
{
    public function execute(string $payoutId): DriverPayout;
}
