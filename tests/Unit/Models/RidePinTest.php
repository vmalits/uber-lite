<?php

declare(strict_types=1);

use App\Enums\RideStatus;
use App\Models\Ride;
use App\Models\User;

beforeEach(function () {
    $this->rider = User::factory()->create();
});

it('generates a 4-digit PIN', function () {
    $ride = Ride::factory()->create([
        'rider_id' => $this->rider->id,
        'status'   => RideStatus::ACCEPTED,
    ]);

    $pin = $ride->generatePin();

    expect($pin)->toBeString()
        ->toHaveLength(4)
        ->toMatch('/^\d{4}$/')
        ->and($ride->fresh()->ride_pin)->toBe($pin);
});

it('verifies PIN successfully', function () {
    $ride = Ride::factory()->create([
        'rider_id' => $this->rider->id,
        'status'   => RideStatus::ACCEPTED,
        'ride_pin' => '1234',
    ]);

    expect($ride->isPinVerified())->toBeFalse();

    $result = $ride->verifyPin();

    expect($result)->toBeTrue()
        ->and($ride->fresh()->isPinVerified())->toBeTrue()
        ->and($ride->fresh()->pin_verified_at)->not->toBeNull();
});

it('returns false when verifying already verified PIN', function () {
    $ride = Ride::factory()->create([
        'rider_id'        => $this->rider->id,
        'status'          => RideStatus::ACCEPTED,
        'ride_pin'        => '1234',
        'pin_verified_at' => now(),
    ]);

    $result = $ride->verifyPin();

    expect($result)->toBeFalse();
});

it('correctly identifies verified PIN', function () {
    $ride = Ride::factory()->create([
        'rider_id'        => $this->rider->id,
        'status'          => RideStatus::ACCEPTED,
        'ride_pin'        => '1234',
        'pin_verified_at' => now(),
    ]);

    expect($ride->isPinVerified())->toBeTrue();
});

it('correctly identifies unverified PIN', function () {
    $ride = Ride::factory()->create([
        'rider_id' => $this->rider->id,
        'status'   => RideStatus::ACCEPTED,
        'ride_pin' => '1234',
    ]);

    expect($ride->isPinVerified())->toBeFalse();
});

it('generates unique PINs', function () {
    $pins = collect();

    for ($i = 0; $i < 100; $i++) {
        $ride = Ride::factory()->create([
            'rider_id' => $this->rider->id,
            'status'   => RideStatus::ACCEPTED,
        ]);

        $pins->push($ride->generatePin());
    }

    $uniquePins = $pins->unique();

    expect($uniquePins->count())->toBeGreaterThan(90);
});

it('PIN is nullable by default', function () {
    $ride = Ride::factory()->create([
        'rider_id' => $this->rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    expect($ride->ride_pin)->toBeNull()
        ->and($ride->pin_verified_at)->toBeNull();
});
