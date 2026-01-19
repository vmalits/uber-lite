<?php

declare(strict_types=1);

namespace App\Presenters\Admin;

use App\Data\Admin\DriverProfileData;
use App\Data\Admin\DriverStatsData;
use App\Data\User\UserData;
use App\Models\User;
use App\Services\Avatar\AvatarUrlResolver;

final readonly class DriverProfilePresenter implements DriverProfilePresenterInterface
{
    public function __construct(
        private AvatarUrlResolver $avatarResolver,
    ) {}

    public function present(User $driver): DriverProfileData
    {
        return new DriverProfileData(
            user: UserData::fromModel($driver, $this->avatarResolver),
            stats: new DriverStatsData(
                totalRides: (int) ($driver->total_rides ?? 0),
                completedRides: (int) ($driver->completed_rides ?? 0),
                cancelledRides: (int) ($driver->cancelled_rides ?? 0),
                averageRating: (float) ($driver->driver_ride_ratings_avg_rating ?? 0.0),
                totalEarned: (float) ($driver->total_earned ?? 0.0),
            ),
        );
    }
}
