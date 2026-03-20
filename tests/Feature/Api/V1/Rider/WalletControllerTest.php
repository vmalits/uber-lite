<?php

declare(strict_types=1);

use App\Enums\CreditTransactionType;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\CreditTransaction;
use App\Models\User;
use App\Models\WalletTopUp;
use Laravel\Sanctum\Sanctum;

test('guest cannot get wallet balance', function (): void {
    $this->getJson('/api/v1/rider/wallet/balance')
        ->assertUnauthorized();
});

test('rider can get wallet balance', function (): void {
    $user = User::factory()->verified()->create([
        'role'            => UserRole::RIDER,
        'profile_step'    => ProfileStep::COMPLETED,
        'credits_balance' => 15000,
    ]);

    WalletTopUp::factory()->count(2)->create([
        'user_id' => $user->id,
        'status'  => 'pending',
    ]);
    WalletTopUp::factory()->count(3)->create([
        'user_id' => $user->id,
        'status'  => 'completed',
    ]);

    Sanctum::actingAs($user);

    $this->getJson('/api/v1/rider/wallet/balance')
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.balance', 15000)
        ->assertJsonPath('data.currency', 'MDL')
        ->assertJsonPath('data.pending_count', 2);
});

test('rider can create wallet top-up', function (): void {
    $user = User::factory()->verified()->create([
        'role'            => UserRole::RIDER,
        'profile_step'    => ProfileStep::COMPLETED,
        'credits_balance' => 0,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/rider/wallet/top-up', [
        'amount' => 5000,
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.amount', 5000)
        ->assertJsonPath('data.status', 'pending');
});

test('rider cannot create top-up with invalid amount', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/rider/wallet/top-up', ['amount' => 10])
        ->assertUnprocessable();

    $this->postJson('/api/v1/rider/wallet/top-up', ['amount' => 100000])
        ->assertUnprocessable();
});

test('rider can cancel pending top-up', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var WalletTopUp $topUp */
    $topUp = WalletTopUp::factory()->create([
        'user_id' => $user->id,
    ]);

    Sanctum::actingAs($user);

    $this->postJson("/api/v1/rider/wallet/top-up/{$topUp->id}/cancel")
        ->assertNoContent();

    expect($topUp->fresh()->status->value)->toBe('cancelled');
});

test('rider cannot cancel another user top-up', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $otherUser = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var WalletTopUp $topUp */
    $topUp = WalletTopUp::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    Sanctum::actingAs($user);

    $this->postJson("/api/v1/rider/wallet/top-up/{$topUp->id}/cancel")
        ->assertForbidden();
});

test('rider can view wallet transactions', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    CreditTransaction::factory()->count(5)->create([
        'user_id' => $user->id,
        'type'    => CreditTransactionType::WALLET_TOP_UP,
    ]);
    CreditTransaction::factory()->count(3)->create([
        'user_id' => $user->id,
        'type'    => CreditTransactionType::RIDE_PAYMENT,
    ]);

    Sanctum::actingAs($user);

    $this->getJson('/api/v1/rider/wallet/transactions')
        ->assertOk()
        ->assertJsonPath('success', true);
});

test('guest cannot access wallet endpoints', function (): void {
    $this->getJson('/api/v1/rider/wallet/balance')
        ->assertUnauthorized();

    $this->getJson('/api/v1/rider/wallet/transactions')
        ->assertUnauthorized();

    $this->postJson('/api/v1/rider/wallet/top-up', ['amount' => 5000])
        ->assertUnauthorized();
});
