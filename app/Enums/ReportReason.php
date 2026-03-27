<?php

declare(strict_types=1);

namespace App\Enums;

enum ReportReason: string
{
    case INAPPROPRIATE_BEHAVIOR = 'inappropriate_behavior';
    case UNSAFE_DRIVING = 'unsafe_driving';
    case CANCELLATION_ABUSE = 'cancellation_abuse';
    case FARE_DISPUTE = 'fare_dispute';
    case HARASSMENT = 'harassment';
    case FRAUD = 'fraud';
    case VEHICLE_CONDITION = 'vehicle_condition';
    case OTHER = 'other';

    public function label(): string
    {
        return match ($this) {
            self::INAPPROPRIATE_BEHAVIOR => 'Inappropriate Behavior',
            self::UNSAFE_DRIVING         => 'Unsafe Driving',
            self::CANCELLATION_ABUSE     => 'Cancellation Abuse',
            self::FARE_DISPUTE           => 'Fare Dispute',
            self::HARASSMENT             => 'Harassment',
            self::FRAUD                  => 'Fraud',
            self::VEHICLE_CONDITION      => 'Vehicle Condition',
            self::OTHER                  => 'Other',
        };
    }
}
