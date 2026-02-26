<?php

declare(strict_types=1);

namespace App\Listeners\Gamification;

use App\Actions\Gamification\CheckAchievementProgressAction;
use App\Enums\AchievementCategory;
use App\Enums\RideStatus;
use App\Events\Rider\RideStatusChanged;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class CheckRiderAchievements
{
    public function __construct(
        private CheckAchievementProgressAction $checkAchievementProgress,
    ) {}

    public function handle(RideStatusChanged $event): void
    {
        if ($event->to !== RideStatus::COMPLETED) {
            return;
        }

        $ride = $event->ride;
        $rider = $ride->rider;

        $this->checkAchievementProgress->checkRides($rider, AchievementCategory::RIDER);

        $this->checkSpendingAchievements($rider);

        $this->checkExplorerAchievements($rider);
    }

    private function checkSpendingAchievements(User $rider): void
    {
        $totalSpent = (int) DB::table('rides')
            ->where('rider_id', $rider->id)
            ->where('status', RideStatus::COMPLETED->value)
            ->sum('price');

        $this->checkAchievementProgress->checkSpending($rider, $totalSpent);
    }

    private function checkExplorerAchievements(User $rider): void
    {
        $uniqueDestinations = DB::table('rides')
            ->where('rider_id', $rider->id)
            ->where('status', RideStatus::COMPLETED->value)
            ->distinct('destination_address')
            ->count('destination_address');

        $this->checkAchievementProgress->checkUniqueDestinations($rider, $uniqueDestinations);
    }
}
