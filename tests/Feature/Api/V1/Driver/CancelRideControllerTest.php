<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('driver can cancel their own active ride', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::ACCEPTED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/cancel");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Ride cancelled successfully.',
            'data'    => [
                'id'     => $ride->id,
                'status' => RideStatus::CANCELLED->value,
            ],
        ]);

    $this->assertDatabaseHas('rides', [
        'id'     => $ride->id,
        'status' => RideStatus::CANCELLED->value,
    ]);
});

test('driver cannot cancel someone else\'s ride', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $otherDriver */
    $otherDriver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id' => $otherDriver->id,
        'status'    => RideStatus::ACCEPTED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/cancel");

    $response->assertForbidden();
});

test('driver cannot cancel completed ride', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/cancel");

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['ride']);
});

test('driver with incomplete profile cannot cancel ride', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::ACCEPTED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/cancel");

    $response->assertForbidden();
});
