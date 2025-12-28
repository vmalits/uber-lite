<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('driver can mark ride as on the way', function (): void {
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

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/on-the-way");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Driver is on the way to pickup location.',
        ]);

    $ride->refresh();
    expect($ride->status)->toBe(RideStatus::ON_THE_WAY);
});

it('driver cannot mark ride as on the way if not the assigned driver', function (): void {
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
        'status'    => RideStatus::ACCEPTED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/on-the-way");

    $response->assertForbidden();
});

it('driver cannot mark ride as on the way if ride is not accepted', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id' => $user->id,
        'status'    => RideStatus::PENDING,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/on-the-way");

    $response->assertForbidden();
});

it('guest cannot mark ride as on the way', function (): void {
    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'status' => RideStatus::ACCEPTED,
    ]);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/on-the-way");

    $response->assertUnauthorized();
});

it('driver with incomplete profile cannot mark ride as on the way', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id' => $user->id,
        'status'    => RideStatus::ACCEPTED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/on-the-way");

    $response->assertForbidden();
});

it('rider cannot mark ride as on the way', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'status' => RideStatus::ACCEPTED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/on-the-way");

    $response->assertForbidden();
});
