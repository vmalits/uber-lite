<?php

declare(strict_types=1);

namespace App\Queries\Device;

use App\Models\DeviceToken;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetDeviceTokensQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, DeviceToken>
     */
    public function execute(string $userId, int $perPage): LengthAwarePaginator;
}
