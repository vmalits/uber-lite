<?php

declare(strict_types=1);

namespace App\Actions\Ride;

use App\Data\Ride\CreateRideMessageData;
use App\Events\Ride\RideMessageCreated;
use App\Models\Ride;
use App\Models\RideMessage;
use App\Models\User;
use Throwable;

final readonly class CreateRideMessageAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $sender, Ride $ride, CreateRideMessageData $data): RideMessage
    {
        $message = RideMessage::query()->create([
            'ride_id'   => $ride->id,
            'sender_id' => $sender->id,
            'message'   => $data->message,
        ]);

        $message->load('sender');

        event(new RideMessageCreated($message));

        return $message;
    }
}
