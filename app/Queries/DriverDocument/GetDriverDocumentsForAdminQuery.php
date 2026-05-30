<?php

declare(strict_types=1);

namespace App\Queries\DriverDocument;

use App\Models\DriverDocument;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

final class GetDriverDocumentsForAdminQuery implements GetDriverDocumentsForAdminQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, DriverDocument>
     */
    public function execute(User $driver): LengthAwarePaginator
    {
        $baseQuery = $driver->documents();

        return QueryBuilder::for($baseQuery)
            ->allowedSorts(['created_at', 'type', 'status'])
            ->defaultSort('-created_at')
            ->paginate(request()->integer('per_page', 15));
    }
}
