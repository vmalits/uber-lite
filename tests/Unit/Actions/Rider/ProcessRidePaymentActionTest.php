<?php

declare(strict_types=1);

use App\Actions\Rider\ProcessRidePaymentAction;
use App\Data\Rider\PayRideData;
use App\Enums\CreditTransactionType;
use App\Enums\PaymentStatus;
use App\Enums\RideStatus;
use App\Models\PaymentAttempt;
use App\Models\PaymentMethod;
use App\Models\Ride;
use App\Models\User;
use App\Services\Payment\FakePaymentService;
use App\Services\Payment\PaymentServiceInterface;
use Illuminate\Validation\ValidationException;

beforeEach(function (): void {
    $this->app->scoped(PaymentServiceInterface::class, fn (): FakePaymentService => new FakePaymentService);
});

test('processes card payment for completed ride', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'credits_balance' => 0,
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

    /** @var PaymentMethod $paymentMethod */
    $paymentMethod = PaymentMethod::factory()->create([
        'user_id' => $user->id,
    ]);

    $action = app(ProcessRidePaymentAction::class);
    $data = new PayRideData(payment_method_id: $paymentMethod->id);

    $result = $action->handle($user, $ride, $data);

    expect($result->status)->toBe(PaymentStatus::COMPLETED)
        ->and($result->amount_paid)->toBe(15000)
        ->and($result->card_charged)->toBe(15000)
        ->and($result->credits_used)->toBe(0)
        ->and($result->fully_paid)->toBeTrue();

    $this->assertDatabaseHas('payment_attempts', [
        'ride_id'           => $ride->id,
        'status'            => PaymentStatus::COMPLETED->value,
        'amount'            => 15000,
        'payment_method_id' => $paymentMethod->id,
    ]);
});

test('processes payment with partial credits', function (): void {
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

    /** @var PaymentMethod $paymentMethod */
    $paymentMethod = PaymentMethod::factory()->create([
        'user_id' => $user->id,
    ]);

    $action = app(ProcessRidePaymentAction::class);
    $data = new PayRideData(payment_method_id: $paymentMethod->id, credits_to_use: 5000);

    $result = $action->handle($user, $ride, $data);

    expect($result->credits_used)->toBe(5000)
        ->and($result->card_charged)->toBe(10000)
        ->and($result->remaining_balance)->toBe(0);

    $this->assertDatabaseHas('credit_transactions', [
        'user_id' => $user->id,
        'amount'  => -5000,
        'type'    => CreditTransactionType::RIDE_PAYMENT->value,
    ]);
});

test('throws exception for non-completed ride', function (): void {
    /** @var User $user */
    $user = User::factory()->create();

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::PENDING,
        'price'    => 10000,
    ]);

    /** @var PaymentMethod $paymentMethod */
    $paymentMethod = PaymentMethod::factory()->create([
        'user_id' => $user->id,
    ]);

    $action = app(ProcessRidePaymentAction::class);
    $data = new PayRideData(payment_method_id: $paymentMethod->id);

    $action->handle($user, $ride, $data);
})->throws(ValidationException::class);

test('throws exception for already paid ride', function (): void {
    /** @var User $user */
    $user = User::factory()->create();

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

    /** @var PaymentMethod $paymentMethod */
    $paymentMethod = PaymentMethod::factory()->create([
        'user_id' => $user->id,
    ]);

    $action = app(ProcessRidePaymentAction::class);
    $data = new PayRideData(payment_method_id: $paymentMethod->id);

    $action->handle($user, $ride, $data);
})->throws(ValidationException::class);

test('throws exception for payment method belonging to another user', function (): void {
    /** @var User $user */
    $user = User::factory()->create();

    /** @var User $otherUser */
    $otherUser = User::factory()->create();

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

    /** @var PaymentMethod $paymentMethod */
    $paymentMethod = PaymentMethod::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    $action = app(ProcessRidePaymentAction::class);
    $data = new PayRideData(payment_method_id: $paymentMethod->id);

    $action->handle($user, $ride, $data);
})->throws(ValidationException::class);

test('handles zero price ride', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'credits_balance' => 0,
    ]);

    /** @var User $driver */
    $driver = User::factory()->create();

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id'     => $user->id,
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 0,
        'completed_at' => now(),
    ]);

    /** @var PaymentMethod $paymentMethod */
    $paymentMethod = PaymentMethod::factory()->create([
        'user_id' => $user->id,
    ]);

    $action = app(ProcessRidePaymentAction::class);
    $data = new PayRideData(payment_method_id: $paymentMethod->id);

    $result = $action->handle($user, $ride, $data);

    expect($result->status)->toBe(PaymentStatus::COMPLETED)
        ->and($result->amount_paid)->toBe(0)
        ->and($result->remaining_balance)->toBe(0)
        ->and($result->fully_paid)->toBeTrue();
});
