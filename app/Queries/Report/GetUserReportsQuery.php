<?php

declare(strict_types=1);

namespace App\Queries\Report;

use App\Models\Report;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class GetUserReportsQuery implements GetUserReportsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Report>
     */
    public function execute(string $userId, int $perPage): LengthAwarePaginator
    {
        return Report::query()
            ->where('reporter_id', $userId)
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
