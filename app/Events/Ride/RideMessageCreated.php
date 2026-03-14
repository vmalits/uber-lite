<?php

declare(strict_types=1);

namespace App\Events\Ride;

use App\Data\Ride\RideMessageData;
use App\Models\RideMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class RideMessageCreated implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly RideMessage $message,
    ) {}

    public function broadcastOn(): array
    {
        $this->message->loadMissing('ride.rider', 'ride.driver');

        $channels = [];

        if ($this->message->ride->rider) {
            $channels[] = new Channel("rider:{$this->message->ride->rider_id}");
        }

        if ($this->message->ride->driver) {
            $channels[] = new Channel("driver:{$this->message->ride->driver_id}");
        }

        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'ride.message_created';
    }

    /**
     * @return array{ride_id: string, message: array<string, mixed>}
     */
    public function broadcastWith(): array
    {
        $this->message->loadMissing('sender');

        /** @var array<string, mixed> $messageData */
        $messageData = RideMessageData::fromModel($this->message)->toArray();

        return [
            'ride_id' => $this->message->ride_id,
            'message' => $messageData,
        ];
    }
}
