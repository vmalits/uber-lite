<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('driver can start ride', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id' => $user->id,
        'status'    => RideStatus::ARRIVED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/start");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Ride has started.',
        ]);

    $ride->refresh();
    expect($ride->status)->toBe(RideStatus::STARTED);
    expect($ride->started_at)->not->toBeNull();
});

it('driver cannot start ride if not the assigned driver', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
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
        'status'    => RideStatus::ARRIVED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/start");

    $response->assertForbidden();
});

it('driver cannot start ride if ride is not arrived', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id' => $user->id,
        'status'    => RideStatus::ON_THE_WAY,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/start");

    $response->assertForbidden();
});

it('guest cannot start ride', function (): void {
    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'status' => RideStatus::ARRIVED,
    ]);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/start");

    $response->assertUnauthorized();
});

it('driver with incomplete profile cannot start ride', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id' => $user->id,
        'status'    => RideStatus::ARRIVED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/start");

    $response->assertForbidden();
});

it('rider cannot start ride', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'status' => RideStatus::ARRIVED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/start");

    $response->assertForbidden();
});
