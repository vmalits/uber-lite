<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;

final class GetVehiclesQuery implements GetVehiclesQueryInterface
{
    /**
     * @return Collection<int, Vehicle>
     */
    public function execute(User $user): Collection
    {
        $baseQuery = $user->vehicles();

        /** @var Collection<int, Vehicle> $vehicles */
        $vehicles = QueryBuilder::for($baseQuery)
            ->allowedSorts(['created_at', 'brand', 'year'])
            ->defaultSort('-created_at')
            ->get();

        return $vehicles;
    }
}
