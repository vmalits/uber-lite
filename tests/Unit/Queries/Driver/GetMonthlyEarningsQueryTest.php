<?php

declare(strict_types=1);

use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use App\Queries\Driver\GetMonthlyEarningsQuery;
use Carbon\Carbon;

beforeEach(function () {
    $this->driver = User::factory()->create(['role' => UserRole::DRIVER]);
    $this->query = app(GetMonthlyEarningsQuery::class);
});

it('returns zero earnings for driver with no rides', function (): void {
    $result = $this->query->execute($this->driver, 1);

    expect($result->totalEarnings)->toBe(0)
        ->and($result->totalRides)->toBe(0);
});

it('calculates monthly earnings correctly', function (): void {
    Carbon::setTestNow('2026-02-28');

    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 1000,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => '2026-02-15 10:00:00',
    ]);

    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 1500,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => '2026-02-20 14:00:00',
    ]);

    $result = $this->query->execute($this->driver, 1);

    expect($result->totalEarnings)->toBe(2500)
        ->and($result->totalRides)->toBe(2);

    Carbon::setTestNow();
});

it('ignores cancelled rides', function (): void {
    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 1000,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 2000,
        'status'       => RideStatus::CANCELLED,
        'completed_at' => now(),
    ]);

    $result = $this->query->execute($this->driver, 1);

    expect($result->totalRides)->toBe(1)
        ->and($result->totalEarnings)->toBe(1000);
});

it('calculates comparison to previous period', function (): void {
    Carbon::setTestNow('2026-03-15');

    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 2000,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => '2026-03-10 10:00:00',
    ]);

    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 1000,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => '2026-02-10 10:00:00',
    ]);

    $result = $this->query->execute($this->driver, 2);

    expect($result->comparisonToPrevious)->toBe(100.0);

    Carbon::setTestNow();
});

it('finds best month', function (): void {
    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 1000,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => '2026-02-10 10:00:00',
    ]);

    Ride::factory()->create([
        'driver_id'    => $this->driver->id,
        'price'        => 5000,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => '2026-01-15 10:00:00',
    ]);

    $result = $this->query->execute($this->driver, 3);

    expect($result->bestMonth)->toBe('2026-01')
        ->and($result->bestMonthEarnings)->toBe(5000);
});
