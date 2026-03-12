<?php

declare(strict_types=1);

namespace App\Data\Rider;

use App\Data\DateData;
use App\Enums\RideStatus;
use App\Models\User;
use Spatie\LaravelData\Data;

/**
 * @param string $id
 * @param string|null $first_name
 * @param string|null $last_name
 * @param DateData $referred_at
 * @param bool $has_completed_ride
 */
final class ReferralData extends Data
{
    public function __construct(
        public string $id,
        public ?string $first_name,
        public ?string $last_name,
        public DateData $referred_at,
        public bool $has_completed_ride,
    ) {}

    public static function fromModel(User $user): self
    {
        return new self(
            id: $user->id,
            first_name: $user->first_name,
            last_name: $user->last_name,
            referred_at: DateData::fromCarbon($user->referred_at ?? now()),
            has_completed_ride: $user->riderRides()->where('status', RideStatus::COMPLETED)->exists(),
        );
    }
}
