<?php

declare(strict_types=1);

use App\Enums\CreditTransactionType;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\CreditTransaction;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    $this->endpoint = '/api/v1/rider/credits/transactions';
});

test('rider can get their credit transactions', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    CreditTransaction::factory()->count(5)->create([
        'user_id' => $rider->id,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson($this->endpoint);

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'items',
                'pagination' => [
                    'current_page',
                    'per_page',
                    'total',
                    'last_page',
                ],
            ],
        ])
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.pagination.total', 5);
});

test('rider can filter transactions by type', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    CreditTransaction::factory()->referralBonus()->count(3)->create([
        'user_id' => $rider->id,
    ]);

    CreditTransaction::factory()->ridePayment()->count(2)->create([
        'user_id' => $rider->id,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson($this->endpoint.'?filter[type]=referral_bonus');

    $response->assertOk()
        ->assertJsonPath('data.pagination.total', 3);
});

test('rider can filter transactions by date range', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    CreditTransaction::factory()->create([
        'user_id'    => $rider->id,
        'created_at' => now()->subDays(10),
    ]);

    CreditTransaction::factory()->create([
        'user_id'    => $rider->id,
        'created_at' => now()->subDays(2),
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson($this->endpoint.'?filter[from]='.now()->subDays(5)->toDateString());

    $response->assertOk()
        ->assertJsonPath('data.pagination.total', 1);
});

test('rider can paginate transactions', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    CreditTransaction::factory()->count(25)->create([
        'user_id' => $rider->id,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson($this->endpoint.'?per_page=10');

    $response->assertOk()
        ->assertJsonPath('data.pagination.per_page', 10)
        ->assertJsonPath('data.pagination.total', 25)
        ->assertJsonPath('data.pagination.last_page', 3);
});

test('rider with no transactions gets empty list', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson($this->endpoint);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.pagination.total', 0)
        ->assertJsonPath('data.items', []);
});

test('guest cannot get credit transactions', function (): void {
    $response = $this->getJson($this->endpoint);

    $response->assertUnauthorized();
});

test('driver cannot get rider credit transactions', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson($this->endpoint);

    $response->assertForbidden();
});

test('transaction data includes correct structure', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    CreditTransaction::factory()->create([
        'user_id'       => $rider->id,
        'amount'        => 500,
        'balance_after' => 1500,
        'type'          => CreditTransactionType::REFERRAL_BONUS,
        'description'   => 'Referral bonus for inviting friend',
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson($this->endpoint);

    $response->assertOk()
        ->assertJsonPath('data.items.0.amount', 500)
        ->assertJsonPath('data.items.0.balance_after', 1500)
        ->assertJsonPath('data.items.0.type', 'referral_bonus')
        ->assertJsonPath('data.items.0.description', 'Referral bonus for inviting friend');
});

test('transactions are sorted by created_at descending', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $old = CreditTransaction::factory()->create([
        'user_id'    => $rider->id,
        'created_at' => now()->subDays(5),
    ]);

    $new = CreditTransaction::factory()->create([
        'user_id'    => $rider->id,
        'created_at' => now(),
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson($this->endpoint);

    $response->assertOk();

    $transactions = $response->json('data.items');

    expect($transactions)->toHaveCount(2)
        ->and($transactions[0]['id'])->toBe($new->id)
        ->and($transactions[1]['id'])->toBe($old->id);
});
