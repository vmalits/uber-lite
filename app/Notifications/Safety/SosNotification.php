<?php

declare(strict_types=1);

namespace App\Notifications\Safety;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class SosNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly User $user,
        private readonly float $latitude,
        private readonly float $longitude,
        private readonly ?string $message = null,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $userName = trim(($this->user->first_name ?? '').' '.($this->user->last_name ?? ''));
        $googleMapsUrl = 'https://www.google.com/maps?q='.$this->latitude.','.$this->longitude;

        $mail = (new MailMessage)
            ->subject('Emergency SOS Alert')
            ->greeting('Emergency Alert!')
            ->line('**'.$userName.'** has triggered an SOS emergency alert.')
            ->line('**Location:**')
            ->action('View Location on Google Maps', $googleMapsUrl)
            ->line('Coordinates: '.$this->latitude.', '.$this->longitude);

        if ($this->message !== null) {
            $mail->line('**Message:** '.$this->message);
        }

        $mail->line('Please try to contact them immediately or alert local authorities.');

        return $mail;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id'   => $this->user->id,
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
            'message'   => $this->message,
        ];
    }
}
