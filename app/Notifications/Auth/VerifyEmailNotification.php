<?php

declare(strict_types=1);

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

final class VerifyEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $userId,
        private readonly string $emailHash,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = URL::temporarySignedRoute(
            name: 'verification.verify',
            expiration: now()->addMinutes(60),
            parameters: [
                'user' => $this->userId,
                'hash' => $this->emailHash,
            ],
        );

        return (new MailMessage)
            ->subject('Verify your email address')
            ->line('Please verify your email address by clicking the button below. This link will expire in 60 minutes.')
            ->action('Verify Email', $url)
            ->line('If you did not create an account, no further action is required.');
    }
}
