<?php

declare(strict_types=1);

use App\Enums\PaymentStatus;
use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\PaymentAttempt;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('rider can pay with credits for completed ride', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'            => UserRole::RIDER,
        'profile_step'    => ProfileStep::COMPLETED,
        'credits_balance' => 15000,
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
        'price'        => 15000,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/pay-with-credits");

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.status', 'completed')
        ->assertJsonPath('data.amount_paid', 15000)
        ->assertJsonPath('data.credits_used', 15000)
        ->assertJsonPath('data.card_charged', 0)
        ->assertJsonPath('data.remaining_balance', 0)
        ->assertJsonPath('data.fully_paid', true);

    $this->assertDatabaseHas('payment_attempts', [
        'ride_id'      => $ride->id,
        'status'       => PaymentStatus::COMPLETED->value,
        'amount'       => 15000,
        'credits_used' => 15000,
        'card_amount'  => 0,
    ]);

    $this->assertDatabaseHas('credit_transactions', [
        'user_id' => $user->id,
        'amount'  => -15000,
    ]);

    expect($user->refresh()->credits_balance)->toBe(0);
});

test('cannot pay with insufficient credits', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'            => UserRole::RIDER,
        'profile_step'    => ProfileStep::COMPLETED,
        'credits_balance' => 5000,
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
        'price'        => 15000,
        'completed_at' => now(),
    ]);

    Sanctum::actingAs($user);

    $this->postJson("/api/v1/rider/rides/{$ride->id}/pay-with-credits")
        ->assertUnprocessable();

    expect($user->refresh()->credits_balance)->toBe(5000);

    $this->assertDatabaseMissing('payment_attempts', [
        'ride_id' => $ride->id,
    ]);
});

test('cannot pay credits for non-completed ride', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'            => UserRole::RIDER,
        'profile_step'    => ProfileStep::COMPLETED,
        'credits_balance' => 15000,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::PENDING,
        'price'    => 10000,
    ]);

    Sanctum::actingAs($user);

    $this->postJson("/api/v1/rider/rides/{$ride->id}/pay-with-credits")
        ->assertForbidden();
});

test('cannot pay credits for already paid ride', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'            => UserRole::RIDER,
        'profile_step'    => ProfileStep::COMPLETED,
        'credits_balance' => 15000,
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
        'price'        => 15000,
        'completed_at' => now(),
    ]);

    PaymentAttempt::factory()->completed()->create([
        'user_id' => $user->id,
        'ride_id' => $ride->id,
        'amount'  => 15000,
    ]);

    Sanctum::actingAs($user);

    $this->postJson("/api/v1/rider/rides/{$ride->id}/pay-with-credits")
        ->assertForbidden();
});

test('unauthenticated user cannot pay with credits', function (): void {
    $this->postJson('/api/v1/rider/rides/some-id/pay-with-credits')
        ->assertUnauthorized();
});
