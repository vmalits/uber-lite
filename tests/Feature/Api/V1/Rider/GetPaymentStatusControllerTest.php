<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\PaymentAttempt;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('rider can get payment status for ride', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'     => $user->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    PaymentAttempt::factory()->completed('ch_test_123')->create([
        'user_id' => $user->id,
        'ride_id' => $ride->id,
        'amount'  => 15000,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/payment-status");

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.status', 'completed')
        ->assertJsonPath('data.amount', 15000)
        ->assertJsonPath('data.provider_transaction_id', 'ch_test_123');
});

test('returns null data when no payment attempt exists', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/payment-status");

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data', null);
});

test('returns latest payment attempt when multiple exist', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'     => $user->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => now(),
    ]);

    PaymentAttempt::factory()->failed('Insufficient funds')->create([
        'user_id' => $user->id,
        'ride_id' => $ride->id,
        'amount'  => 15000,
    ]);

    PaymentAttempt::factory()->completed('ch_success_456')->create([
        'user_id' => $user->id,
        'ride_id' => $ride->id,
        'amount'  => 15000,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/payment-status");

    $response->assertOk()
        ->assertJsonPath('data.status', 'completed')
        ->assertJsonPath('data.provider_transaction_id', 'ch_success_456');
});

test('unauthenticated user cannot get payment status', function (): void {
    $this->getJson('/api/v1/rider/rides/some-id/payment-status')
        ->assertUnauthorized();
});

test('rider cannot get payment status for another riders ride', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $otherRider */
    $otherRider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $otherRider->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $this->getJson("/api/v1/rider/rides/{$ride->id}/payment-status")
        ->assertForbidden();
});

test('returns failed payment status correctly', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    PaymentAttempt::factory()->failed('Card declined')->create([
        'user_id' => $user->id,
        'ride_id' => $ride->id,
        'amount'  => 10000,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/payment-status");

    $response->assertOk()
        ->assertJsonPath('data.status', 'failed')
        ->assertJsonPath('data.failure_reason', 'Card declined');
});
