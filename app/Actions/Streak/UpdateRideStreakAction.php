<?php

declare(strict_types=1);

namespace App\Actions\Streak;

use App\Enums\StreakLevel;
use App\Events\Streak\StreakBroken;
use App\Events\Streak\StreakLevelUp;
use App\Events\Streak\StreakUpdated;
use App\Models\User;
use App\Models\UserRideStreak;
use Carbon\CarbonInterface;

final readonly class UpdateRideStreakAction
{
    public function handle(User $user, CarbonInterface $rideDate): UserRideStreak
    {
        $streak = UserRideStreak::query()->firstOrCreate(
            ['user_id' => $user->id],
            [
                'current_streak'    => 0,
                'longest_streak'    => 0,
                'last_ride_date'    => null,
                'streak_started_at' => null,
            ],
        );

        $previousLevel = StreakLevel::fromStreak($streak->current_streak);
        $previousStreak = $streak->current_streak;

        if ($streak->last_ride_date === null) {
            $streak->current_streak = 1;
            $streak->longest_streak = 1;
            $streak->last_ride_date = $rideDate->copy()->startOfDay();
            $streak->streak_started_at = now();
        } elseif ($streak->last_ride_date->isSameDay($rideDate)) {
            return $streak;
        } elseif ($streak->last_ride_date->isYesterday()) {
            $streak->current_streak++;
            $streak->last_ride_date = $rideDate->copy()->startOfDay();

            if ($streak->current_streak > $streak->longest_streak) {
                $streak->longest_streak = $streak->current_streak;
            }
        } elseif ($streak->last_ride_date->diffInDays($rideDate) > 1) {
            StreakBroken::dispatch($user, $streak->current_streak);

            $streak->current_streak = 1;
            $streak->last_ride_date = $rideDate->copy()->startOfDay();
            $streak->streak_started_at = now();
        }

        $streak->save();

        $newLevel = StreakLevel::fromStreak($streak->current_streak);

        if ($newLevel !== $previousLevel && $newLevel !== StreakLevel::NONE) {
            StreakLevelUp::dispatch($user, $streak->current_streak, $newLevel);
        }

        StreakUpdated::dispatch($user, $streak->current_streak, $previousStreak);

        return $streak;
    }
}
