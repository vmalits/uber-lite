<?php

declare(strict_types=1);

use App\Enums\PaymentStatus;
use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\PaymentAttempt;
use App\Models\PaymentMethod;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('rider can pay for a completed ride', function (): void {
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
        'price'        => 15000,
        'completed_at' => now(),
    ]);

    /** @var PaymentMethod $paymentMethod */
    $paymentMethod = PaymentMethod::factory()->create([
        'user_id' => $user->id,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/pay", [
        'payment_method_id' => $paymentMethod->id,
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.status', 'completed')
        ->assertJsonPath('data.amount_paid', 15000)
        ->assertJsonPath('data.card_charged', 15000)
        ->assertJsonPath('data.credits_used', 0)
        ->assertJsonPath('data.fully_paid', true);

    $this->assertDatabaseHas('payment_attempts', [
        'ride_id'           => $ride->id,
        'status'            => PaymentStatus::COMPLETED->value,
        'amount'            => 15000,
        'payment_method_id' => $paymentMethod->id,
    ]);
});

test('rider can pay with partial credits', function (): void {
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

    /** @var PaymentMethod $paymentMethod */
    $paymentMethod = PaymentMethod::factory()->create([
        'user_id' => $user->id,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/pay", [
        'payment_method_id' => $paymentMethod->id,
        'credits_to_use'    => 5000,
    ]);

    $response->assertOk()
        ->assertJsonPath('data.credits_used', 5000)
        ->assertJsonPath('data.card_charged', 10000)
        ->assertJsonPath('data.remaining_balance', 0);

    expect($user->refresh()->credits_balance)->toBe(0);
});

test('credits are capped at available balance and ride price', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'            => UserRole::RIDER,
        'profile_step'    => ProfileStep::COMPLETED,
        'credits_balance' => 2000,
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

    /** @var PaymentMethod $paymentMethod */
    $paymentMethod = PaymentMethod::factory()->create([
        'user_id' => $user->id,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/pay", [
        'payment_method_id' => $paymentMethod->id,
        'credits_to_use'    => 10000,
    ]);

    $response->assertOk()
        ->assertJsonPath('data.credits_used', 2000)
        ->assertJsonPath('data.card_charged', 13000);
});

test('cannot pay for non-completed ride', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::PENDING,
    ]);

    /** @var PaymentMethod $paymentMethod */
    $paymentMethod = PaymentMethod::factory()->create([
        'user_id' => $user->id,
    ]);

    Sanctum::actingAs($user);

    $this->postJson("/api/v1/rider/rides/{$ride->id}/pay", [
        'payment_method_id' => $paymentMethod->id,
    ])->assertForbidden();
});

test('cannot pay for already paid ride', function (): void {
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
        'price'        => 15000,
        'completed_at' => now(),
    ]);

    PaymentAttempt::factory()->completed()->create([
        'user_id' => $user->id,
        'ride_id' => $ride->id,
        'amount'  => 15000,
    ]);

    /** @var PaymentMethod $paymentMethod */
    $paymentMethod = PaymentMethod::factory()->create([
        'user_id' => $user->id,
    ]);

    Sanctum::actingAs($user);

    $this->postJson("/api/v1/rider/rides/{$ride->id}/pay", [
        'payment_method_id' => $paymentMethod->id,
    ])->assertForbidden();
});

test('cannot pay with another users payment method', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $otherUser */
    $otherUser = User::factory()->verified()->create([
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
        'price'        => 15000,
        'completed_at' => now(),
    ]);

    /** @var PaymentMethod $paymentMethod */
    $paymentMethod = PaymentMethod::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    Sanctum::actingAs($user);

    $this->postJson("/api/v1/rider/rides/{$ride->id}/pay", [
        'payment_method_id' => $paymentMethod->id,
    ])->assertUnprocessable();
});

test('cannot pay for another riders ride', function (): void {
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

    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'     => $otherRider->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 15000,
        'completed_at' => now(),
    ]);

    /** @var PaymentMethod $paymentMethod */
    $paymentMethod = PaymentMethod::factory()->create([
        'user_id' => $user->id,
    ]);

    Sanctum::actingAs($user);

    $this->postJson("/api/v1/rider/rides/{$ride->id}/pay", [
        'payment_method_id' => $paymentMethod->id,
    ])->assertForbidden();
});

test('unauthenticated user cannot pay', function (): void {
    $this->postJson('/api/v1/rider/rides/some-id/pay', [
        'payment_method_id' => 'some-id',
    ])->assertUnauthorized();
});

test('validation fails without payment_method_id', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::COMPLETED,
        'price'    => 10000,
    ]);

    Sanctum::actingAs($user);

    $this->postJson("/api/v1/rider/rides/{$ride->id}/pay", [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['payment_method_id']);
});
