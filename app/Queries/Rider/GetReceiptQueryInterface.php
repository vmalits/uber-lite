<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\ReceiptData;
use App\Models\Ride;

interface GetReceiptQueryInterface
{
    public function execute(Ride $ride): ReceiptData;
}
