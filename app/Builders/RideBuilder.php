<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\RideStatus;
use App\Models\Ride;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of Ride
 *
 * @extends Builder<TModelClass>
 */
final class RideBuilder extends Builder
{
    /**
     * @return RideBuilder<TModelClass>
     */
    public function active(): self
    {
        return $this->whereIn('status', [
            RideStatus::PENDING,
            RideStatus::ACCEPTED,
            RideStatus::ON_THE_WAY,
            RideStatus::ARRIVED,
            RideStatus::STARTED,
        ]);
    }

    public function latestActive(): ?Ride
    {
        return $this->active()->latest()->first();
    }
}
