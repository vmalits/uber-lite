<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\RideMessage;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('rider can get unread messages count', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id'  => $rider->id,
        'driver_id' => $driver->id,
    ]);

    RideMessage::factory()->create([
        'ride_id'    => $ride->id,
        'sender_id'  => $driver->id,
        'read_at'    => null,
    ]);
    RideMessage::factory()->create([
        'ride_id'    => $ride->id,
        'sender_id'  => $driver->id,
        'read_at'    => null,
    ]);

    actingAs($rider)
        ->getJson("/api/v1/ride/{$ride->id}/messages/unread-count")
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.count', 2);
});

test('driver can get unread messages count', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id'  => $rider->id,
        'driver_id' => $driver->id,
    ]);

    RideMessage::factory()->create([
        'ride_id'    => $ride->id,
        'sender_id'  => $rider->id,
        'read_at'    => null,
    ]);

    actingAs($driver)
        ->getJson("/api/v1/ride/{$ride->id}/messages/unread-count")
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.count', 1);
});

test('returns zero when all messages are read', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id'  => $rider->id,
        'driver_id' => $driver->id,
    ]);

    RideMessage::factory()->read()->create([
        'ride_id'   => $ride->id,
        'sender_id' => $driver->id,
    ]);

    actingAs($rider)
        ->getJson("/api/v1/ride/{$ride->id}/messages/unread-count")
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.count', 0);
});

test('excludes own messages from unread count', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id'  => $rider->id,
        'driver_id' => $driver->id,
    ]);

    RideMessage::factory()->create([
        'ride_id'   => $ride->id,
        'sender_id' => $rider->id,
        'read_at'   => null,
    ]);
    RideMessage::factory()->create([
        'ride_id'   => $ride->id,
        'sender_id' => $rider->id,
        'read_at'   => null,
    ]);

    actingAs($rider)
        ->getJson("/api/v1/ride/{$ride->id}/messages/unread-count")
        ->assertOk()
        ->assertJsonPath('data.count', 0);
});

test('returns zero when ride has no messages', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id'  => $rider->id,
        'driver_id' => $driver->id,
    ]);

    actingAs($rider)
        ->getJson("/api/v1/ride/{$ride->id}/messages/unread-count")
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.count', 0);
});

test('non-participant cannot get unread messages count', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var User $outsider */
    $outsider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id'  => $rider->id,
        'driver_id' => $driver->id,
    ]);

    actingAs($outsider)
        ->getJson("/api/v1/ride/{$ride->id}/messages/unread-count")
        ->assertForbidden();
});

test('unauthenticated user cannot get unread messages count', function (): void {
    $ride = Ride::factory()->create();

    getJson("/api/v1/ride/{$ride->id}/messages/unread-count")
        ->assertUnauthorized();
});

test('returns 404 for non-existent ride', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/ride/nonexistent/messages/unread-count')
        ->assertNotFound();
});
