<?php

declare(strict_types=1);

namespace App\Queries\DriverDocument;

use App\Models\DriverDocument;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;

final class GetDriverDocumentsQuery implements GetDriverDocumentsQueryInterface
{
    /**
     * @return Collection<int, DriverDocument>
     */
    public function execute(User $user): Collection
    {
        $baseQuery = $user->documents();

        /** @var Collection<int, DriverDocument> $documents */
        $documents = QueryBuilder::for($baseQuery)
            ->allowedSorts(['created_at', 'type'])
            ->defaultSort('-created_at')
            ->get();

        return $documents;
    }
}
