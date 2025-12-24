<?php

declare(strict_types=1);

namespace App\Events\Rider;

use App\Models\Ride;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class RideCreated implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly Ride $ride,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("rider:{$this->ride->rider_id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ride.created';
    }

    /**
     * @return array{
     *     ride_id: int|string,
     *     status: string,
     *     from: array{address: string|null, lat: float|null, lng: float|null},
     *     to: array{address: string|null, lat: float|null, lng: float|null}
     * }
     */
    public function broadcastWith(): array
    {
        return [
            'ride_id' => $this->ride->id,
            'status'  => $this->ride->status->value,
            'from'    => [
                'address' => $this->ride->origin_address,
                'lat'     => $this->ride->origin_lat,
                'lng'     => $this->ride->origin_lng,
            ],
            'to' => [
                'address' => $this->ride->destination_address,
                'lat'     => $this->ride->destination_lat,
                'lng'     => $this->ride->destination_lng,
            ],
        ];
    }
}
