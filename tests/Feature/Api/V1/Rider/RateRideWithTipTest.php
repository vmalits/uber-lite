<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('rider can add tip when rating ride', function (): void {
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

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'     => $rider->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    actingAs($rider)
        ->putJson("/api/v1/rider/rides/{$ride->id}/rating", [
            'rating'  => 5,
            'comment' => 'Great ride!',
            'tip'     => [
                'amount'  => 500,
                'comment' => 'Thank you!',
            ],
        ])
        ->assertStatus(200)
        ->assertJsonPath('success', true);

    $ride->refresh();
    expect($ride->tip)->not->toBeNull()
        ->and($ride->tip->amount)->toBe(500)
        ->and($ride->tip->comment)->toBe('Thank you!');
});

test('tip is optional when rating ride', function (): void {
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

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'     => $rider->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    actingAs($rider)
        ->putJson("/api/v1/rider/rides/{$ride->id}/rating", [
            'rating'  => 5,
            'comment' => 'Great ride!',
        ])
        ->assertStatus(200)
        ->assertJsonPath('success', true);

    $ride->refresh();
    expect($ride->tip)->toBeNull();
});

test('cannot add tip to incomplete ride', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::STARTED,
    ]);

    actingAs($rider)
        ->putJson("/api/v1/rider/rides/{$ride->id}/rating", [
            'rating'  => 5,
            'comment' => 'Great ride!',
            'tip'     => [
                'amount' => 500,
            ],
        ])
        ->assertStatus(403);
});

test('tip amount must be valid', function (): void {
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

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'     => $rider->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    actingAs($rider)
        ->putJson("/api/v1/rider/rides/{$ride->id}/rating", [
            'rating'  => 5,
            'comment' => 'Great ride!',
            'tip'     => [
                'amount' => -100,
            ],
        ])
        ->assertStatus(422);
});
