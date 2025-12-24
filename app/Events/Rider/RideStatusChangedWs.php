<?php

declare(strict_types=1);

namespace App\Events\Rider;

use App\Enums\RideStatus;
use App\Models\Ride;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

final class RideStatusChangedWs implements ShouldBroadcast
{
    public function __construct(
        public Ride $ride,
        public RideStatus $from,
        public RideStatus $to,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("rider:{$this->ride->rider_id}"),
            new Channel("driver:{$this->ride->driver_id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ride.status_changed';
    }

    /**
     * @return array{ride_id: string, from: string, to: string}
     */
    public function broadcastWith(): array
    {
        return [
            'ride_id' => $this->ride->id,
            'from'    => $this->from->value,
            'to'      => $this->to->value,
        ];
    }
}
