<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('driver can mark ride as arrived', function (): void {
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

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/arrived");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Driver has arrived at pickup location.',
        ]);

    $ride->refresh();
    expect($ride->status)->toBe(RideStatus::ARRIVED);
    expect($ride->arrived_at)->not->toBeNull();
});

it('driver cannot mark ride as arrived if not the assigned driver', function (): void {
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
        'status'    => RideStatus::ON_THE_WAY,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/arrived");

    $response->assertForbidden();
});

it('driver cannot mark ride as arrived if ride is not on the way', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id' => $user->id,
        'status'    => RideStatus::ACCEPTED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/arrived");

    $response->assertForbidden();
});

it('guest cannot mark ride as arrived', function (): void {
    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'status' => RideStatus::ON_THE_WAY,
    ]);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/arrived");

    $response->assertUnauthorized();
});

it('driver with incomplete profile cannot mark ride as arrived', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id' => $user->id,
        'status'    => RideStatus::ON_THE_WAY,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/arrived");

    $response->assertForbidden();
});

it('rider cannot mark ride as arrived', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'status' => RideStatus::ON_THE_WAY,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/arrived");

    $response->assertForbidden();
});
