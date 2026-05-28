<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\PaymentAttempt;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetPaymentsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, PaymentAttempt>
     */
    public function execute(int $perPage): LengthAwarePaginator;
}
