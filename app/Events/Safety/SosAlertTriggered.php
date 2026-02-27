<?php

declare(strict_types=1);

namespace App\Events\Safety;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class SosAlertTriggered implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly float $latitude,
        public readonly float $longitude,
        public readonly ?string $message = null,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("user:{$this->user->id}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'sos.alert';
    }

    /**
     * @return array{
     *     user_id: string,
     *     latitude: float,
     *     longitude: float,
     *     message: string|null,
     *     timestamp: string
     * }
     */
    public function broadcastWith(): array
    {
        return [
            'user_id'   => $this->user->id,
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
            'message'   => $this->message,
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
