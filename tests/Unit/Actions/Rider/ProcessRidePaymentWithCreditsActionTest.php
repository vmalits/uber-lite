<?php

declare(strict_types=1);

use App\Actions\Rider\ProcessRidePaymentWithCreditsAction;
use App\Enums\CreditTransactionType;
use App\Enums\PaymentStatus;
use App\Enums\RideStatus;
use App\Models\PaymentAttempt;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Validation\ValidationException;

test('pays with credits for completed ride', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'credits_balance' => 15000,
    ]);

    /** @var User $driver */
    $driver = User::factory()->create();

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'     => $user->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 15000,
        'completed_at' => now(),
    ]);

    $action = app(ProcessRidePaymentWithCreditsAction::class);

    $result = $action->handle($user, $ride);

    expect($result->status)->toBe(PaymentStatus::COMPLETED)
        ->and($result->amount_paid)->toBe(15000)
        ->and($result->credits_used)->toBe(15000)
        ->and($result->card_charged)->toBe(0)
        ->and($result->remaining_balance)->toBe(0)
        ->and($result->fully_paid)->toBeTrue();

    $this->assertDatabaseHas('payment_attempts', [
        'ride_id'      => $ride->id,
        'status'       => PaymentStatus::COMPLETED->value,
        'credits_used' => 15000,
        'card_amount'  => 0,
    ]);

    $this->assertDatabaseHas('credit_transactions', [
        'user_id' => $user->id,
        'amount'  => -15000,
        'type'    => CreditTransactionType::RIDE_PAYMENT->value,
    ]);

    expect($user->refresh()->credits_balance)->toBe(0);
});

test('throws exception for insufficient credits', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'credits_balance' => 5000,
    ]);

    /** @var User $driver */
    $driver = User::factory()->create();

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'     => $user->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 15000,
        'completed_at' => now(),
    ]);

    $action = app(ProcessRidePaymentWithCreditsAction::class);

    $action->handle($user, $ride);
})->throws(ValidationException::class);

test('does not debit credits on failure', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'credits_balance' => 5000,
    ]);

    /** @var User $driver */
    $driver = User::factory()->create();

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'     => $user->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 15000,
        'completed_at' => now(),
    ]);

    $action = app(ProcessRidePaymentWithCreditsAction::class);

    try {
        $action->handle($user, $ride);
    } catch (ValidationException) {
        // expected
    }

    expect($user->refresh()->credits_balance)->toBe(5000);

    $this->assertDatabaseMissing('payment_attempts', [
        'ride_id' => $ride->id,
    ]);

    $this->assertDatabaseMissing('credit_transactions', [
        'user_id' => $user->id,
        'amount'  => -5000,
    ]);
});

test('throws exception for non-completed ride', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'credits_balance' => 15000,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::PENDING,
        'price'    => 10000,
    ]);

    $action = app(ProcessRidePaymentWithCreditsAction::class);

    $action->handle($user, $ride);
})->throws(ValidationException::class);

test('throws exception for already paid ride', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'credits_balance' => 15000,
    ]);

    /** @var User $driver */
    $driver = User::factory()->create();

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'     => $user->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 10000,
        'completed_at' => now(),
    ]);

    PaymentAttempt::factory()->completed()->create([
        'user_id' => $user->id,
        'ride_id' => $ride->id,
        'amount'  => 10000,
    ]);

    $action = app(ProcessRidePaymentWithCreditsAction::class);

    $action->handle($user, $ride);
})->throws(ValidationException::class);
