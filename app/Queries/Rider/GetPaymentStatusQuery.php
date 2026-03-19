<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\PaymentAttemptData;
use App\Models\PaymentAttempt;
use App\Models\Ride;

final readonly class GetPaymentStatusQuery implements GetPaymentStatusQueryInterface
{
    public function execute(Ride $ride): ?PaymentAttemptData
    {
        $attempt = PaymentAttempt::query()
            ->where('ride_id', $ride->id)
            ->latest()
            ->first();

        if ($attempt === null) {
            return null;
        }

        return PaymentAttemptData::fromModel($attempt);
    }
}
