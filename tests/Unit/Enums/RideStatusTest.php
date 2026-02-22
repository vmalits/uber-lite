<?php

declare(strict_types=1);

use App\Enums\RideStatus;

it('has all expected cases', function () {
    $expected = [
        'PENDING', 'ACCEPTED', 'ON_THE_WAY', 'ARRIVED', 'STARTED', 'COMPLETED', 'CANCELLED', 'SCHEDULED',
    ];
    $actual = array_map(fn ($c) => $c->name, RideStatus::cases());
    expect($actual)->toEqualCanonicalizing($expected);
});

it('correctly identifies active statuses', function () {
    $active = [
        RideStatus::SCHEDULED,
        RideStatus::PENDING,
        RideStatus::ACCEPTED,
        RideStatus::ON_THE_WAY,
        RideStatus::ARRIVED,
        RideStatus::STARTED,
    ];
    foreach ($active as $status) {
        expect($status->isActive())->toBeTrue();
    }
    $inactive = [RideStatus::COMPLETED, RideStatus::CANCELLED];
    foreach ($inactive as $status) {
        expect($status->isActive())->toBeFalse();
    }
});

it('correctly identifies final statuses', function () {
    $final = [RideStatus::COMPLETED, RideStatus::CANCELLED];
    foreach ($final as $status) {
        expect($status->isFinal())->toBeTrue();
    }
    $notFinal = [
        RideStatus::SCHEDULED,
        RideStatus::PENDING,
        RideStatus::ACCEPTED,
        RideStatus::ON_THE_WAY,
        RideStatus::ARRIVED,
        RideStatus::STARTED,
    ];
    foreach ($notFinal as $status) {
        expect($status->isFinal())->toBeFalse();
    }
});
