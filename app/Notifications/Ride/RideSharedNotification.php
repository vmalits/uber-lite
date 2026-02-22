<?php

declare(strict_types=1);

namespace App\Notifications\Ride;

use App\Data\Ride\Share\ShareRideData;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class RideSharedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Ride $ride,
        private readonly ShareRideData $shareData,
    ) {}

    /**
     * @return array<int, class-string>
     */
    public function via(object $notifiable): array
    {
        $channels = [DatabaseChannel::class];

        if ($this->shareData->contact_email !== null) {
            $channels[] = MailChannel::class;
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $shareUrl = url('/track-ride/'.$this->ride->id);
        /** @var User $rider */
        $rider = $this->ride->rider;
        $riderName = trim(($rider->first_name ?? '').' '.($rider->last_name ?? ''));

        return (new MailMessage)
            ->subject('🚗 Someone is sharing their ride with you')
            ->greeting("Hi {$this->shareData->contact_name}!")
            ->line("{$riderName} is sharing their ride with you.")
            ->line("From: {$this->ride->origin_address}")
            ->line("To: {$this->ride->destination_address}")
            ->line("Status: {$this->ride->status->value}")
            ->when(
                $this->shareData->message !== null,
                fn (MailMessage $m) => $m->line("Message: {$this->shareData->message}"),
            )
            ->action('Track Ride', $shareUrl)
            ->line('You can track this ride in real-time using the link above.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ride_id'             => $this->ride->id,
            'contact_name'        => $this->shareData->contact_name,
            'contact_phone'       => $this->shareData->contact_phone,
            'contact_email'       => $this->shareData->contact_email,
            'origin_address'      => $this->ride->origin_address,
            'destination_address' => $this->ride->destination_address,
            'status'              => $this->ride->status->value,
            'message'             => $this->shareData->message,
        ];
    }
}
