<?php

declare(strict_types=1);

namespace App\Notifications\Gamification;

use App\Models\Achievement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Channels\DatabaseChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class AchievementUnlockedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly Achievement $achievement,
        private readonly string $tier,
        private readonly int $totalXp,
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
            ->subject('Achievement Unlocked!')
            ->greeting("Congratulations, {$userName}!")
            ->line("You've unlocked the **{$this->achievement->name}** achievement!")
            ->line("Description: {$this->achievement->description}")
            ->line("Points earned: {$this->achievement->points_reward} XP")
            ->line("Your current tier: {$this->tier}")
            ->line("Total XP: {$this->achievement->points_reward}");
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'achievement_id' => $this->achievement->id,
            'name'           => $this->achievement->name,
            'description'    => $this->achievement->description,
            'icon'           => $this->achievement->icon,
            'points'         => $this->achievement->points_reward,
            'tier'           => $this->tier,
            'total_xp'       => $this->totalXp,
        ];
    }
}
