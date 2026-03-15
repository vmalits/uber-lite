<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\DriverRatingSummaryData;
use App\Enums\RideStatus;
use App\Models\RideRating;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class GetDriverRatingSummaryQuery implements GetDriverRatingSummaryQueryInterface
{
    public function execute(string $driverId): DriverRatingSummaryData
    {
        $averageRating = (float) RideRating::query()
            ->whereHas('ride', function (Builder $query) use ($driverId): void {
                $query->where('driver_id', $driverId)
                    ->where('status', RideStatus::COMPLETED);
            })
            ->avg('rating');

        $totalReviews = (int) RideRating::query()
            ->whereHas('ride', function (Builder $query) use ($driverId): void {
                $query->where('driver_id', $driverId)
                    ->where('status', RideStatus::COMPLETED);
            })
            ->count();

        $distribution = $this->getRatingDistribution($driverId);

        $platformAverage = $this->getPlatformAverage();

        $percentile = $this->calculatePercentile($averageRating);

        return new DriverRatingSummaryData(
            average_rating: round($averageRating, 2),
            total_reviews: $totalReviews,
            rating_distribution: $distribution,
            platform_average: round($platformAverage, 2),
            percentile: $percentile,
        );
    }

    /**
     * @return array{5: int, 4: int, 3: int, 2: int, 1: int}
     */
    private function getRatingDistribution(string $driverId): array
    {
        /** @var Collection<int, object{rating: int, count: int}> $results */
        $results = RideRating::query()
            ->whereHas('ride', function (Builder $query) use ($driverId): void {
                $query->where('driver_id', $driverId)
                    ->where('status', RideStatus::COMPLETED);
            })
            ->select('rating', DB::raw('COUNT(*) as count'))
            ->groupBy('rating')
            ->get();

        $distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

        foreach ($results as $row) {
            $rating = $row->rating;
            if (isset($distribution[$rating])) {
                $distribution[$rating] = $row->count;
            }
        }

        return $distribution;
    }

    private function getPlatformAverage(): float
    {
        return (float) RideRating::query()
            ->whereHas('ride', function (Builder $query): void {
                $query->where('status', RideStatus::COMPLETED);
            })
            ->avg('rating');
    }

    private function calculatePercentile(float $averageRating): int
    {
        if ($averageRating >= 4.9) {
            return 98;
        }
        if ($averageRating >= 4.8) {
            return 90;
        }
        if ($averageRating >= 4.7) {
            return 80;
        }
        if ($averageRating >= 4.6) {
            return 70;
        }
        if ($averageRating >= 4.5) {
            return 60;
        }
        if ($averageRating >= 4.0) {
            return 40;
        }
        if ($averageRating >= 3.0) {
            return 20;
        }

        return 10;
    }
}
