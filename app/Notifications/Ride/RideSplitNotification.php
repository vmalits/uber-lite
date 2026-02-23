<?php

declare(strict_types=1);

namespace App\Notifications\Ride;

use App\Data\Ride\Split\ParticipantData;
use App\Data\Ride\Split\SplitRideData;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class RideSplitNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Ride $ride,
        private readonly ParticipantData $participant,
        private readonly SplitRideData $data,
    ) {}

    /**
     * @return array<int, class-string>
     */
    public function via(object $notifiable): array
    {
        $channels = [DatabaseChannel::class];

        if ($this->participant->email !== null) {
            $channels[] = MailChannel::class;
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $rideUrl = url('/track-ride/'.$this->ride->id);

        /** @var User $rider */
        $rider = $this->ride->rider;
        $riderName = trim(($rider->first_name ?? '').' '.($rider->last_name ?? ''));

        return (new MailMessage)
            ->subject('Ride split invitation')
            ->greeting("Hi {$this->participant->name}!")
            ->line("{$riderName} invited you to split a ride.")
            ->line("From: {$this->ride->origin_address}")
            ->line("To: {$this->ride->destination_address}")
            ->when(
                $this->data->note !== null,
                fn (MailMessage $m) => $m->line("Note: {$this->data->note}"),
            )
            ->action('Track Ride', $rideUrl)
            ->line('You can track this ride in real-time using the link above.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ride_id'             => $this->ride->id,
            'participant_name'    => $this->participant->name,
            'participant_email'   => $this->participant->email,
            'participant_phone'   => $this->participant->phone,
            'participant_share'   => $this->participant->share,
            'origin_address'      => $this->ride->origin_address,
            'destination_address' => $this->ride->destination_address,
        ];
    }
}
