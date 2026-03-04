<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\PaymentMethodData;
use App\Models\PaymentMethod;

final readonly class GetPaymentMethodsQuery implements GetPaymentMethodsQueryInterface
{
    /**
     * @return array<int, PaymentMethodData>
     */
    public function execute(string $userId): array
    {
        $paymentMethods = PaymentMethod::query()
            ->where('user_id', $userId)
            ->orderByDesc('is_default')
            ->orderByDesc('created_at')
            ->get();

        return $paymentMethods
            ->map(fn (PaymentMethod $method): PaymentMethodData => PaymentMethodData::fromModel($method))
            ->all();
    }
}
