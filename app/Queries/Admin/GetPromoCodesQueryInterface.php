<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\PromoCode;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetPromoCodesQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, PromoCode>
     */
    public function execute(int $perPage): LengthAwarePaginator;
}
