<?php

declare(strict_types=1);

namespace App\Events\Rider;

use App\Enums\RideStatus;
use App\Models\Ride;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RideStatusChanged
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly Ride $ride,
        public readonly RideStatus $from,
        public readonly RideStatus $to,
        public readonly string $actorType,
        public readonly ?string $actorId = null,
    ) {}
}
