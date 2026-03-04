<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\ReceiptData;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetReceiptsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, ReceiptData>
     */
    public function execute(
        string $riderId,
        int $perPage,
        ?string $from = null,
        ?string $to = null,
    ): LengthAwarePaginator;
}
