<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\PaymentMethodData;

interface GetPaymentMethodsQueryInterface
{
    /**
     * @return array<int, PaymentMethodData>
     */
    public function execute(string $userId): array;
}
