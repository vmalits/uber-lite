<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;

test('driver can complete their own started ride', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id'  => $driver->id,
        'status'     => RideStatus::STARTED,
        'started_at' => now(),
    ]);

    Sanctum::actingAs($driver);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/complete");

    $response->assertOk()
        ->assertJson([
            'message' => 'Ride completed successfully.',
        ]);

    $this->assertDatabaseHas('rides', [
        'id'     => $ride->id,
        'status' => RideStatus::COMPLETED->value,
    ]);

    $this->assertGreaterThan(0, $ride->fresh()->price);
    $this->assertNotNull($ride->fresh()->completed_at);
});

test('driver cannot complete someone else\'s ride', function (): void {
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
        'driver_id'  => $otherDriver->id,
        'status'     => RideStatus::STARTED,
        'started_at' => now(),
    ]);

    Sanctum::actingAs($driver);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/complete");

    $response->assertForbidden();
});

test('driver cannot complete a ride that is not started', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($driver);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/complete");

    $response->assertForbidden();
});

test('driver with incomplete profile cannot complete ride', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'driver_id'  => $driver->id,
        'status'     => RideStatus::STARTED,
        'started_at' => now(),
    ]);

    Sanctum::actingAs($driver);

    $response = $this->postJson("/api/v1/driver/rides/{$ride->id}/complete");

    $response->assertForbidden();
});

test('returns not found for non-existent ride', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
    $nonExistentRideId = Str::ulid();

    Sanctum::actingAs($driver);

    $response = $this->postJson("/api/v1/driver/rides/{$nonExistentRideId}/complete");

    $response->assertNotFound();
});
