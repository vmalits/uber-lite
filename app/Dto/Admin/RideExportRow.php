<?php

declare(strict_types=1);

namespace App\Dto\Admin;

use App\Models\Ride;
use App\Models\User;
use Carbon\CarbonInterface;
use Stringable;

final readonly class RideExportRow implements Stringable
{
    public function __construct(
        public string $id,
        public string $status,
        public string $riderId,
        public ?string $riderName,
        public ?string $riderPhone,
        public ?string $driverId,
        public ?string $driverName,
        public ?string $driverPhone,
        public string $originAddress,
        public string $destinationAddress,
        public ?string $price,
        public ?string $discountAmount,
        public ?float $estimatedDistanceKm,
        public ?float $estimatedDurationMin,
        public ?string $createdAt,
        public ?string $startedAt,
        public ?string $completedAt,
        public ?string $cancelledAt,
        public ?string $cancelledBy,
        public ?string $cancellationReason,
    ) {}

    public static function fromModel(Ride $ride): self
    {
        return new self(
            id: $ride->id,
            status: $ride->status->value,
            riderId: $ride->rider_id,
            riderName: self::formatUserName($ride->rider),
            riderPhone: $ride->rider->phone,
            driverId: $ride->driver_id,
            driverName: self::formatUserName($ride->driver),
            driverPhone: $ride->driver?->phone,
            originAddress: $ride->origin_address,
            destinationAddress: $ride->destination_address,
            price: self::formatPrice($ride->price),
            discountAmount: self::formatPrice($ride->discount_amount),
            estimatedDistanceKm: $ride->estimated_distance_km,
            estimatedDurationMin: $ride->estimated_duration_min,
            createdAt: self::formatDateTime($ride->created_at),
            startedAt: self::formatDateTime($ride->started_at),
            completedAt: self::formatDateTime($ride->completed_at),
            cancelledAt: self::formatDateTime($ride->cancelled_at),
            cancelledBy: $ride->cancelled_by_type?->value,
            cancellationReason: $ride->cancelled_reason,
        );
    }

    public function __toString(): string
    {
        return $this->id;
    }

    private static function formatUserName(?User $user): ?string
    {
        if ($user === null) {
            return null;
        }

        return trim($user->first_name.' '.$user->last_name) ?: null;
    }

    private static function formatPrice(?int $cents): ?string
    {
        if ($cents === null) {
            return null;
        }

        return number_format($cents / 100, 2);
    }

    private static function formatDateTime(?CarbonInterface $dateTime): ?string
    {
        return $dateTime?->format('Y-m-d H:i:s');
    }
}
