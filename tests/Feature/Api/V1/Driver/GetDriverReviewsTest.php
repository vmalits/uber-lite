<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\RideRating;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('driver can get reviews', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    RideRating::factory()->forRide($ride)->create([
        'rating'  => 5,
        'comment' => 'Great driver!',
    ]);

    actingAs($driver)
        ->getJson('/api/v1/driver/reviews')
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.items.0.rating', 5);
});

test('driver sees only own reviews', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var User $otherDriver */
    $otherDriver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride1 = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    $ride2 = Ride::factory()->create([
        'driver_id' => $otherDriver->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    RideRating::factory()->forRide($ride1)->create([
        'rating' => 5,
    ]);

    RideRating::factory()->forRide($ride2)->create([
        'rating' => 1,
    ]);

    actingAs($driver)
        ->getJson('/api/v1/driver/reviews')
        ->assertStatus(200)
        ->assertJsonPath('data.items.0.rating', 5)
        ->assertJsonMissing(['rating' => 1]);
});

test('unauthenticated user cannot access reviews', function (): void {
    getJson('/api/v1/driver/reviews')
        ->assertStatus(401);
});

test('rider cannot access driver reviews', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/driver/reviews')
        ->assertStatus(403);
});

test('driver can sort reviews by rating', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride1 = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    $ride2 = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::COMPLETED,
    ]);

    RideRating::factory()->forRide($ride1)->create([
        'rating' => 3,
    ]);

    RideRating::factory()->forRide($ride2)->create([
        'rating' => 5,
    ]);

    actingAs($driver)
        ->getJson('/api/v1/driver/reviews?sort=-rating')
        ->assertStatus(200)
        ->assertJsonPath('data.items.0.rating', 5)
        ->assertJsonPath('data.items.1.rating', 3);
});

test('empty reviews returns empty array', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($driver)
        ->getJson('/api/v1/driver/reviews')
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.items', []);
});
