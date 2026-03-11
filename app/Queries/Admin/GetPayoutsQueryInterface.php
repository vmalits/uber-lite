<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Data\Admin\GetPayoutsData;
use App\Models\DriverPayout;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetPayoutsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, DriverPayout>
     */
    public function execute(GetPayoutsData $data): LengthAwarePaginator;
}
