<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Admin;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\CreditTransaction;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

function createAdminForCreditTransactions(): User
{
    return User::factory()->create([
        'role'              => UserRole::ADMIN,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
}

test('admin can list user credit transactions', function (): void {
    $admin = createAdminForCreditTransactions();
    $user = User::factory()->create();
    CreditTransaction::factory()->count(3)->referralBonus()->create(['user_id' => $user->id]);
    CreditTransaction::factory()->count(2)->ridePayment()->create(['user_id' => $user->id]);

    actingAs($admin)
        ->getJson("/api/v1/admin/users/{$user->id}/credits/history")
        ->assertOk()
        ->assertJsonCount(5, 'data.items');
});

test('admin can filter user credit transactions by type', function (): void {
    $admin = createAdminForCreditTransactions();
    $user = User::factory()->create();
    CreditTransaction::factory()->count(2)->referralBonus()->create(['user_id' => $user->id]);
    CreditTransaction::factory()->count(3)->ridePayment()->create(['user_id' => $user->id]);

    actingAs($admin)
        ->getJson("/api/v1/admin/users/{$user->id}/credits/history?filter[type]=referral_bonus")
        ->assertOk()
        ->assertJsonCount(2, 'data.items');
});

test('admin can filter user credit transactions by direction', function (): void {
    $admin = createAdminForCreditTransactions();
    $user = User::factory()->create();
    CreditTransaction::factory()->count(2)->referralBonus()->create(['user_id' => $user->id]);
    CreditTransaction::factory()->count(3)->ridePayment()->create(['user_id' => $user->id]);

    actingAs($admin)
        ->getJson("/api/v1/admin/users/{$user->id}/credits/history?filter[direction]=debit")
        ->assertOk()
        ->assertJsonCount(3, 'data.items');
});

test('admin can filter user credit transactions by date range', function (): void {
    $admin = createAdminForCreditTransactions();
    $user = User::factory()->create();
    CreditTransaction::factory()->referralBonus()->create(['user_id' => $user->id, 'created_at' => '2026-01-15']);
    CreditTransaction::factory()->referralBonus()->create(['user_id' => $user->id, 'created_at' => '2026-03-15']);

    actingAs($admin)
        ->getJson("/api/v1/admin/users/{$user->id}/credits/history?filter[from]=2026-03-01&filter[to]=2026-03-31")
        ->assertOk()
        ->assertJsonCount(1, 'data.items');
});

test('admin user credit transactions returns empty for user with no transactions', function (): void {
    $admin = createAdminForCreditTransactions();
    $user = User::factory()->create();

    actingAs($admin)
        ->getJson("/api/v1/admin/users/{$user->id}/credits/history")
        ->assertOk()
        ->assertJsonCount(0, 'data.items');
});

test('admin can sort user credit transactions by amount', function (): void {
    $admin = createAdminForCreditTransactions();
    $user = User::factory()->create();
    CreditTransaction::factory()->adminAdjustment()->create(['user_id' => $user->id, 'amount' => 500]);
    CreditTransaction::factory()->adminAdjustment()->create(['user_id' => $user->id, 'amount' => -200]);

    $response = actingAs($admin)
        ->getJson("/api/v1/admin/users/{$user->id}/credits/history?sort=amount")
        ->assertOk();

    $items = $response->json('data.items');
    expect($items[0]['amount'])->toBeLessThan($items[1]['amount']);
});

test('non-admin cannot list user credit transactions', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
    $user = User::factory()->create();

    actingAs($rider)
        ->getJson("/api/v1/admin/users/{$user->id}/credits/history")
        ->assertForbidden();
});

test('unauthenticated user cannot list credit transactions', function (): void {
    $user = User::factory()->create();

    getJson("/api/v1/admin/users/{$user->id}/credits/history")
        ->assertUnauthorized();
});

test('response has correct data structure', function (): void {
    $admin = createAdminForCreditTransactions();
    $user = User::factory()->create();
    CreditTransaction::factory()->referralBonus()->create(['user_id' => $user->id]);

    actingAs($admin)
        ->getJson("/api/v1/admin/users/{$user->id}/credits/history")
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'items' => [
                    '*' => ['id', 'amount', 'balance_after', 'type', 'description', 'created_at'],
                ],
                'pagination',
            ],
        ]);
});
