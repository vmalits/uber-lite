<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

final readonly class GetDriverQuery implements GetDriverQueryInterface
{
    public function execute(string $id): User
    {
        /** @var User $driver */
        $driver = User::query()
            ->where('role', UserRole::DRIVER)
            ->withCount([
                'driverRides as total_rides',
                'driverRides as completed_rides' => fn (Builder $query) => $query->where(
                    'status', RideStatus::COMPLETED,
                ),
                'driverRides as cancelled_rides' => fn (Builder $query) => $query->where(
                    'status', RideStatus::CANCELLED,
                ),
            ])
            ->addSelect([
                'driver_ride_ratings_avg_rating' => DB::table('ride_ratings')
                    ->selectRaw('AVG(rating)')
                    ->join('rides', 'rides.id', '=', 'ride_ratings.ride_id')
                    ->whereColumn('rides.driver_id', 'users.id'),
            ])
            ->findOrFail($id);

        return $driver;
    }
}
