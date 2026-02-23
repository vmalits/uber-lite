<?php

declare(strict_types=1);

namespace App\Actions\Ride;

use App\Data\Ride\Split\RideSplitData;
use App\Models\Ride;
use App\Models\RideSplit;
use Illuminate\Database\Eloquent\Collection;

final readonly class GetRideSplitsAction
{
    /**
     * @return array<int, RideSplitData>
     */
    public function handle(Ride $ride): array
    {
        /** @var Collection<int, RideSplit> $splits */
        $splits = $ride->splits()->orderBy('created_at')->get();

        return $splits->map(
            fn (RideSplit $split) => RideSplitData::fromModel($split),
        )->all();
    }
}
