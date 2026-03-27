<?php

declare(strict_types=1);

namespace App\Queries\Device;

use App\Models\DeviceToken;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class GetDeviceTokensQuery implements GetDeviceTokensQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, DeviceToken>
     */
    public function execute(string $userId, int $perPage): LengthAwarePaginator
    {
        return DeviceToken::query()
            ->where('user_id', $userId)
            ->orderByDesc('last_used_at')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
