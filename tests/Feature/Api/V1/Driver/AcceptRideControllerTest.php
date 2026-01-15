<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;

test('driver can accept a ride', function () {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'email_verified_at' => now(),
    ]);
    $ride = Ride::factory()->create(['status' => RideStatus::PENDING]);

    $this->actingAs($driver);
    $this->postJson("/api/v1/driver/rides/{$ride->id}/accept")
        ->assertOk()
        ->assertJson([
            'message' => __('messages.ride.accepted'),
        ]);

    $ride->refresh();
    expect($ride->status)->toBe(RideStatus::ACCEPTED);
    expect($ride->driver_id)->toBe($driver->id);
});

test('unauthorized user cannot accept a ride', function () {
    $ride = Ride::factory()->create(['status' => RideStatus::PENDING]);

    $this->postJson("/api/v1/driver/rides/{$ride->id}/accept")
        ->assertUnauthorized();
});

test('driver cannot accept a non-pending ride', function () {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'email_verified_at' => now(),
    ]);
    $ride = Ride::factory()->create(['status' => RideStatus::COMPLETED]);

    $this->actingAs($driver);
    $this->postJson("/api/v1/driver/rides/{$ride->id}/accept")
        ->assertForbidden();
});
