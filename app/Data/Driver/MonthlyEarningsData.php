<?php

declare(strict_types=1);

namespace App\Data\Driver;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class MonthlyEarningsData extends Data
{
    public function __construct(
        #[MapName('total_earnings')]
        public int $totalEarnings,
        #[MapName('total_rides')]
        public int $totalRides,
        #[MapName('average_per_hour')]
        public float $averagePerHour,
        #[MapName('best_month')]
        public ?string $bestMonth,
        #[MapName('best_month_earnings')]
        public int $bestMonthEarnings,
        #[MapName('comparison_to_previous')]
        public float $comparisonToPrevious,
    ) {}

    /**
     * @param array{total_earnings: int, total_rides: int, average_per_hour: float, best_month: string|null, best_month_earnings: int, comparison_to_previous: float} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            totalEarnings: $data['total_earnings'],
            totalRides: $data['total_rides'],
            averagePerHour: $data['average_per_hour'],
            bestMonth: $data['best_month'],
            bestMonthEarnings: $data['best_month_earnings'],
            comparisonToPrevious: $data['comparison_to_previous'],
        );
    }
}
