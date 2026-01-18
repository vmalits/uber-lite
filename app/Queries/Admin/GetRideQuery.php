<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\Ride;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class GetRideQuery implements GetRideQueryInterface
{
    public function execute(string $id): Ride
    {
        /** @var Ride $result */
        $result = QueryBuilder::for(Ride::where('id', $id))
            ->allowedIncludes(['rider', 'driver', 'rating'])
            ->getSubject()
            ->firstOrFail();

        return $result;
    }
}
