<?php

declare(strict_types=1);

namespace App\Actions\Ride;

use App\Data\Ride\Split\ParticipantData;
use App\Data\Ride\Split\SplitRideData;
use App\Data\Ride\Split\SplitRideResponseData;
use App\Models\Ride;
use App\Models\RideSplit;
use App\Notifications\Ride\RideSplitNotification;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Throwable;

final readonly class SplitRideAction
{
    /**
     * @throws Throwable
     */
    public function handle(Ride $ride, SplitRideData $data): SplitRideResponseData
    {
        $invitations = [];

        DB::transaction(function () use ($ride, $data, &$invitations): void {
            $trackUrl = url('/track-ride/'.$ride->id);

            /** @var ParticipantData $participant */
            foreach ($data->participants as $participant) {
                RideSplit::query()->create([
                    'ride_id'           => $ride->id,
                    'participant_name'  => $participant->name,
                    'participant_email' => $participant->email,
                    'participant_phone' => $participant->phone,
                    'share'             => $participant->share,
                ]);

                $invitations[] = [
                    'name'      => $participant->name,
                    'email'     => $participant->email,
                    'phone'     => $participant->phone,
                    'share'     => $participant->share,
                    'track_url' => $trackUrl,
                ];

                if ($participant->email !== null) {
                    $notifiable = new AnonymousNotifiable;
                    $notifiable->route('mail', $participant->email);

                    Notification::send(
                        $notifiable,
                        new RideSplitNotification($ride, $participant, $data),
                    );
                }
            }
        });

        return new SplitRideResponseData(
            ride_id: $ride->id,
            invitations: $invitations,
        );
    }
}
