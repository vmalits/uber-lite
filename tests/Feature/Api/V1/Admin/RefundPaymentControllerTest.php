<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Admin;

use App\Enums\CreditTransactionType;
use App\Enums\PaymentStatus;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\CreditTransaction;
use App\Models\PaymentAttempt;
use App\Models\Ride;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

function createAdminForRefund(): User
{
    return User::factory()->create([
        'role'              => UserRole::ADMIN,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
}

test('admin can refund a completed payment', function (): void {
    $admin = createAdminForRefund();
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'credits_balance'   => 100,
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
    ]);

    $payment = PaymentAttempt::factory()->create([
        'user_id'      => $rider->id,
        'ride_id'      => $ride->id,
        'status'       => PaymentStatus::COMPLETED,
        'amount'       => 5000,
        'credits_used' => 3000,
        'card_amount'  => 2000,
        'completed_at' => now(),
    ]);

    actingAs($admin)
        ->postJson("/api/v1/admin/payments/{$payment->id}/refund", [
            'reason' => 'Duplicate charge',
        ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.status', 'refunded')
        ->assertJsonPath('data.credits_refunded', 3000);

    expect($payment->fresh()->status)->toBe(PaymentStatus::REFUNDED)
        ->and($rider->fresh()->credits_balance)->toBe(3100);
});

test('refund creates credit transaction with refund type', function (): void {
    $admin = createAdminForRefund();
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'credits_balance'   => 500,
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    $payment = PaymentAttempt::factory()->create([
        'user_id'      => $rider->id,
        'ride_id'      => $ride->id,
        'status'       => PaymentStatus::COMPLETED,
        'amount'       => 2000,
        'credits_used' => 2000,
        'card_amount'  => 0,
        'completed_at' => now(),
    ]);

    actingAs($admin)
        ->postJson("/api/v1/admin/payments/{$payment->id}/refund", [
            'reason' => 'Admin refund',
        ]);

    $transaction = CreditTransaction::query()
        ->where('user_id', $rider->id)
        ->where('type', CreditTransactionType::REFUND)
        ->first();

    expect($transaction)->not->toBeNull()
        ->and($transaction->amount)->toBe(2000)
        ->and($transaction->balance_after)->toBe(2500);
});

test('cannot refund non-completed payment', function (): void {
    $admin = createAdminForRefund();
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    $payment = PaymentAttempt::factory()->create([
        'user_id'      => $rider->id,
        'ride_id'      => $ride->id,
        'status'       => PaymentStatus::PENDING,
        'amount'       => 5000,
        'credits_used' => 0,
        'card_amount'  => 0,
    ]);

    actingAs($admin)
        ->postJson("/api/v1/admin/payments/{$payment->id}/refund", [
            'reason' => 'Test refund',
        ])
        ->assertUnprocessable();
});

test('refund with zero credits used does not change balance', function (): void {
    $admin = createAdminForRefund();
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'credits_balance'   => 500,
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    $payment = PaymentAttempt::factory()->create([
        'user_id'      => $rider->id,
        'ride_id'      => $ride->id,
        'status'       => PaymentStatus::COMPLETED,
        'amount'       => 5000,
        'credits_used' => 0,
        'card_amount'  => 5000,
        'completed_at' => now(),
    ]);

    actingAs($admin)
        ->postJson("/api/v1/admin/payments/{$payment->id}/refund", [
            'reason' => 'Full card refund',
        ])
        ->assertOk();

    expect($payment->fresh()->status)->toBe(PaymentStatus::REFUNDED);
    expect($rider->fresh()->credits_balance)->toBe(500);
});

test('reason is optional', function (): void {
    $admin = createAdminForRefund();
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    $payment = PaymentAttempt::factory()->create([
        'user_id'      => $rider->id,
        'ride_id'      => $ride->id,
        'status'       => PaymentStatus::COMPLETED,
        'amount'       => 5000,
        'credits_used' => 0,
        'card_amount'  => 5000,
        'completed_at' => now(),
    ]);

    actingAs($admin)
        ->postJson("/api/v1/admin/payments/{$payment->id}/refund")
        ->assertOk();
});

test('non-admin cannot refund payment', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    $payment = PaymentAttempt::factory()->completed()->create([
        'user_id'      => $rider->id,
        'ride_id'      => $ride->id,
        'amount'       => 5000,
        'credits_used' => 0,
        'card_amount'  => 5000,
        'completed_at' => now(),
    ]);

    actingAs($rider)
        ->postJson("/api/v1/admin/payments/{$payment->id}/refund")
        ->assertForbidden();
});

test('unauthenticated user cannot refund payment', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    $payment = PaymentAttempt::factory()->completed()->create([
        'user_id'      => $rider->id,
        'ride_id'      => $ride->id,
        'amount'       => 5000,
        'credits_used' => 0,
        'card_amount'  => 5000,
        'completed_at' => now(),
    ]);

    postJson("/api/v1/admin/payments/{$payment->id}/refund")
        ->assertUnauthorized();
});
