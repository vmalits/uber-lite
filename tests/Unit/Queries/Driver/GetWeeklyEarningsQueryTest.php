<?php

declare(strict_types=1);

use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use App\Queries\Driver\GetWeeklyEarningsQuery;
use Carbon\Carbon;

beforeEach(function () {
    $this->driver = User::factory()->create(['role' => UserRole::DRIVER]);
    $this->query = app(GetWeeklyEarningsQuery::class);
});

it('returns zero earnings for driver with no rides', function (): void {
    $result = $this->query->execute($this->driver, 1);

    expect($result->totalEarnings)->toBe(0)
        ->and($result->totalRides)->toBe(0);
});

it('calculates weekly earnings correctly', function (): void {
    Carbon::setTestNow('2026-02-28');

    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 100,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => '2026-02-26 10:00:00',
    ]);

    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 150,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => '2026-02-27 14:00:00',
    ]);

    $result = $this->query->execute($this->driver, 1);

    expect($result->totalEarnings)->toBe(250)
        ->and($result->totalRides)->toBe(2);

    Carbon::setTestNow();
});

it('ignores cancelled rides', function (): void {
    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 100,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 200,
        'status'       => RideStatus::CANCELLED,
        'completed_at' => now(),
    ]);

    $result = $this->query->execute($this->driver, 1);

    expect($result->totalRides)->toBe(1)
        ->and($result->totalEarnings)->toBe(100);
});

it('calculates comparison to previous period', function (): void {
    Carbon::setTestNow('2026-02-28');

    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 200,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => '2026-02-26 10:00:00',
    ]);

    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 100,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => '2026-02-12 10:00:00',
    ]);

    $result = $this->query->execute($this->driver, 2);

    expect($result->comparisonToPrevious)->toBe(100.0);

    Carbon::setTestNow();
});

it('finds best day', function (): void {
    Carbon::setTestNow('2026-02-28');

    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 100,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => '2026-02-26 10:00:00',
    ]);

    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 300,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => '2026-02-27 10:00:00',
    ]);

    $result = $this->query->execute($this->driver, 1);

    expect($result->bestDayEarnings)->toBe(300);

    Carbon::setTestNow();
});
