<?php

declare(strict_types=1);

namespace App\Listeners\Gamification;

use App\Actions\Gamification\CheckAchievementProgressAction;
use App\Enums\AchievementCategory;
use App\Enums\RideStatus;
use App\Events\Rider\RideStatusChanged;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class CheckDriverAchievements
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
        $driver = $ride->driver;

        if ($driver === null) {
            return;
        }

        $this->checkAchievementProgress->checkRides($driver, AchievementCategory::DRIVER);

        $this->checkRatingAchievements($driver);
    }

    private function checkRatingAchievements(User $driver): void
    {
        $avgRating = DB::table('ride_ratings')
            ->join('rides', 'rides.id', '=', 'ride_ratings.ride_id')
            ->where('rides.driver_id', $driver->id)
            ->avg('ride_ratings.rating');

        if ($avgRating !== null) {
            $this->checkAchievementProgress->checkRating($driver, (float) $avgRating);
        }
    }
}
