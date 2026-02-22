<?php

declare(strict_types=1);

namespace App\Enums;

final class RideTransitions
{
    public const array MAP = [
        RideStatus::SCHEDULED->value => [
            RideStatus::PENDING,
            RideStatus::CANCELLED,
        ],
        RideStatus::PENDING->value => [
            RideStatus::ACCEPTED,
            RideStatus::CANCELLED,
        ],
        RideStatus::ACCEPTED->value => [
            RideStatus::ON_THE_WAY,
            RideStatus::CANCELLED,
        ],
        RideStatus::ON_THE_WAY->value => [
            RideStatus::ARRIVED,
            RideStatus::CANCELLED,
        ],
        RideStatus::ARRIVED->value => [
            RideStatus::STARTED,
            RideStatus::CANCELLED,
        ],
        RideStatus::STARTED->value => [
            RideStatus::COMPLETED,
            RideStatus::CANCELLED,
        ],
    ];
}
