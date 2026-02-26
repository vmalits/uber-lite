<?php

declare(strict_types=1);

namespace App\Notifications\Gamification;

use App\Enums\UserTier;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class LevelUpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly UserTier $oldTier,
        private readonly UserTier $newTier,
        private readonly int $level,
        private readonly int $xp,
    ) {}

    /**
     * @return array<int, class-string>
     */
    public function via(object $notifiable): array
    {
        return [DatabaseChannel::class];
    }

    public function toMail(object $notifiable): MailMessage
    {
        /** @var User $user */
        $user = $notifiable;
        $userName = trim(($user->first_name ?? '').' '.($user->last_name ?? ''));

        return (new MailMessage)
            ->subject('Level Up!')
            ->greeting("Awesome, {$userName}!")
            ->line("You've leveled up from **{$this->oldTier->value}** to **{$this->newTier->value}**!")
            ->line("Your current level: {$this->level}")
            ->line("Total XP: {$this->xp}")
            ->line('Keep up the great work!');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'old_tier' => $this->oldTier->value,
            'new_tier' => $this->newTier->value,
            'level'    => $this->level,
            'xp'       => $this->xp,
        ];
    }
}
