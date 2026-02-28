<?php

declare(strict_types=1);

namespace App\Notifications\Streak;

use App\Enums\StreakLevel;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class StreakLevelUpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly int $currentStreak,
        private readonly StreakLevel $newLevel,
        private readonly int $discountPercent,
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
        $userName = $notifiable instanceof User ? trim(($notifiable->first_name ?? '').' '.($notifiable->last_name ?? '')) : '';

        return (new MailMessage)
            ->subject(__('messages.streak.level_up_subject'))
            ->greeting(__('messages.streak.level_up_greeting', ['name' => $userName]))
            ->line(__('messages.streak.level_up_message', [
                'streak'   => $this->currentStreak,
                'level'    => $this->newLevel->label(),
                'discount' => $this->discountPercent,
            ]))
            ->line(__('messages.streak.keep_going'));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'current_streak'   => $this->currentStreak,
            'new_level'        => $this->newLevel->value,
            'discount_percent' => $this->discountPercent,
        ];
    }
}
