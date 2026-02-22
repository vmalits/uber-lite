<?php

declare(strict_types=1);

namespace App\Actions\Ride;

use App\Data\Ride\Share\ShareRideData;
use App\Models\Ride;
use App\Notifications\Ride\RideSharedNotification;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;

final readonly class ShareRideAction
{
    public function handle(Ride $ride, ShareRideData $data): void
    {
        $notifiable = new AnonymousNotifiable;

        if ($data->contact_email !== null) {
            $notifiable->route('mail', $data->contact_email);
        }

        Notification::send($notifiable, new RideSharedNotification($ride, $data));
    }
}
