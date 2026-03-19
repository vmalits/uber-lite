<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\PaymentAttemptData;
use App\Models\Ride;

interface GetPaymentStatusQueryInterface
{
    public function execute(Ride $ride): ?PaymentAttemptData;
}
