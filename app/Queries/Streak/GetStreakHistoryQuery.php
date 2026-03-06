<?php

declare(strict_types=1);

namespace App\Queries\Streak;

use App\Data\Streak\StreakHistoryData;
use App\Data\Streak\StreakHistoryResponseData;
use App\Enums\RideStatus;
use App\Models\User;
use App\Models\UserRideStreak;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelData\DataCollection;

final readonly class GetStreakHistoryQuery implements GetStreakHistoryQueryInterface
{
    public function execute(User $user, int $days): StreakHistoryResponseData
    {
        /** @var UserRideStreak|null $streak */
        $streak = UserRideStreak::query()
            ->where('user_id', $user->id)
            ->first();

        $currentStreak = $streak !== null ? $streak->current_streak : 0;
        $longestStreak = $streak !== null ? $streak->longest_streak : 0;

        $history = $this->buildHistory($user, $days);

        return new StreakHistoryResponseData(
            currentStreak: $currentStreak,
            longestStreak: $longestStreak,
            history: new DataCollection(StreakHistoryData::class, $history->all()),
        );
    }

    /**
     * @return Collection<int, StreakHistoryData>
     */
    private function buildHistory(User $user, int $days): Collection
    {
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        /** @var Collection<int, string> $rideDates */
        $rideDates = DB::table('rides')
            ->where('rider_id', $user->id)
            ->where('status', RideStatus::COMPLETED->value)
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->pluck('completed_at')
            ->map(function (mixed $date): string {
                /** @var string $dateString */
                $dateString = $date;

                return Carbon::parse($dateString)->toDateString();
            })
            ->unique()
            ->values();

        $history = new Collection;
        $streakCounter = 0;

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $hasRide = $rideDates->contains($date);

            if ($hasRide) {
                $streakCounter++;
            } else {
                $streakCounter = 0;
            }

            $history->push(new StreakHistoryData(
                streakCount: $streakCounter,
                date: $date,
                rideCompleted: $hasRide,
            ));
        }

        return $history;
    }
}
