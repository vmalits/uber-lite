<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\DriverReceiptData;
use App\Models\Ride;

interface GetDriverReceiptQueryInterface
{
    public function execute(Ride $ride): DriverReceiptData;
}
