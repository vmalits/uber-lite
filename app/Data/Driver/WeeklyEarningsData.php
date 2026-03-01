<?php

declare(strict_types=1);

namespace App\Data\Driver;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class WeeklyEarningsData extends Data
{
    public function __construct(
        #[MapName('total_earnings')]
        public int $totalEarnings,
        #[MapName('total_rides')]
        public int $totalRides,
        #[MapName('average_per_hour')]
        public float $averagePerHour,
        #[MapName('best_day')]
        public ?string $bestDay,
        #[MapName('best_day_earnings')]
        public int $bestDayEarnings,
        #[MapName('comparison_to_previous')]
        public float $comparisonToPrevious,
    ) {}

    /**
     * @param array{total_earnings: int, total_rides: int, average_per_hour: float, best_day: string|null, best_day_earnings: int, comparison_to_previous: float} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            totalEarnings: $data['total_earnings'],
            totalRides: $data['total_rides'],
            averagePerHour: $data['average_per_hour'],
            bestDay: $data['best_day'],
            bestDayEarnings: $data['best_day_earnings'],
            comparisonToPrevious: $data['comparison_to_previous'],
        );
    }
}
