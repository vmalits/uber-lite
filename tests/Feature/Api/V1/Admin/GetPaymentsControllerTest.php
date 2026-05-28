<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Admin;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\PaymentAttempt;
use App\Models\Ride;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

function createAdminForPayments(): User
{
    return User::factory()->create([
        'role'              => UserRole::ADMIN,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
}

test('admin can list payments', function (): void {
    $admin = createAdminForPayments();
    PaymentAttempt::factory()->count(5)->create();

    actingAs($admin)
        ->getJson('/api/v1/admin/payments')
        ->assertOk()
        ->assertJsonCount(5, 'data.items');
});

test('admin can filter payments by status via filter parameter', function (): void {
    $admin = createAdminForPayments();
    PaymentAttempt::factory()->count(3)->completed()->create();
    PaymentAttempt::factory()->count(2)->failed()->create();

    actingAs($admin)
        ->getJson('/api/v1/admin/payments?filter[status]=completed')
        ->assertOk()
        ->assertJsonCount(3, 'data.items');
});

test('admin can filter payments by user_id', function (): void {
    $admin = createAdminForPayments();
    $user = User::factory()->create();
    PaymentAttempt::factory()->count(3)->create(['user_id' => $user->id]);
    PaymentAttempt::factory()->count(2)->create();

    actingAs($admin)
        ->getJson("/api/v1/admin/payments?filter[user_id]={$user->id}")
        ->assertOk()
        ->assertJsonCount(3, 'data.items');
});

test('admin can filter payments by ride_id', function (): void {
    $admin = createAdminForPayments();
    $ride = Ride::factory()->create();
    PaymentAttempt::factory()->count(2)->create(['ride_id' => $ride->id]);
    PaymentAttempt::factory()->count(3)->create();

    actingAs($admin)
        ->getJson("/api/v1/admin/payments?filter[ride_id]={$ride->id}")
        ->assertOk()
        ->assertJsonCount(2, 'data.items');
});

test('admin can sort payments by amount ascending', function (): void {
    $admin = createAdminForPayments();
    $cheap = PaymentAttempt::factory()->create(['amount' => 1000]);
    $expensive = PaymentAttempt::factory()->create(['amount' => 5000]);
    $mid = PaymentAttempt::factory()->create(['amount' => 3000]);

    $response = actingAs($admin)
        ->getJson('/api/v1/admin/payments?sort=amount')
        ->assertOk();

    $items = $response->json('data.items');
    expect($items[0]['id'])->toBe($cheap->id)
        ->and($items[1]['id'])->toBe($mid->id)
        ->and($items[2]['id'])->toBe($expensive->id);
});

test('admin can sort payments by amount descending', function (): void {
    $admin = createAdminForPayments();
    $cheap = PaymentAttempt::factory()->create(['amount' => 1000]);
    $expensive = PaymentAttempt::factory()->create(['amount' => 5000]);

    $response = actingAs($admin)
        ->getJson('/api/v1/admin/payments?sort=-amount')
        ->assertOk();

    $items = $response->json('data.items');
    expect($items[0]['id'])->toBe($expensive->id)
        ->and($items[1]['id'])->toBe($cheap->id);
});

test('admin can sort payments by created_at descending by default', function (): void {
    $admin = createAdminForPayments();
    $oldest = PaymentAttempt::factory()->create(['created_at' => now()->subDays(2)]);
    $newest = PaymentAttempt::factory()->create(['created_at' => now()]);
    $mid = PaymentAttempt::factory()->create(['created_at' => now()->subDay()]);

    $response = actingAs($admin)
        ->getJson('/api/v1/admin/payments')
        ->assertOk();

    $items = $response->json('data.items');
    expect($items[0]['id'])->toBe($newest->id)
        ->and($items[1]['id'])->toBe($mid->id)
        ->and($items[2]['id'])->toBe($oldest->id);
});

test('admin payments returns empty list when no payments exist', function (): void {
    $admin = createAdminForPayments();

    actingAs($admin)
        ->getJson('/api/v1/admin/payments')
        ->assertOk()
        ->assertJsonCount(0, 'data.items');
});

test('admin can paginate payments with per_page parameter', function (): void {
    $admin = createAdminForPayments();
    PaymentAttempt::factory()->count(5)->create();

    actingAs($admin)
        ->getJson('/api/v1/admin/payments?per_page=2')
        ->assertOk()
        ->assertJsonCount(2, 'data.items')
        ->assertJsonPath('data.pagination.total', 5)
        ->assertJsonPath('data.pagination.per_page', 2);
});

test('response has correct data structure', function (): void {
    $admin = createAdminForPayments();
    PaymentAttempt::factory()->completed()->create();

    actingAs($admin)
        ->getJson('/api/v1/admin/payments')
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'items' => [
                    '*' => [
                        'id',
                        'user',
                        'ride_id',
                        'status',
                        'amount',
                        'credits_used',
                        'card_amount',
                        'currency',
                        'provider',
                        'provider_transaction_id',
                        'failure_reason',
                        'completed_at',
                        'created_at',
                    ],
                ],
                'pagination' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                ],
            ],
        ]);
});

test('non-admin cannot list payments', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/admin/payments')
        ->assertForbidden();
});

test('unauthenticated user cannot list payments', function (): void {
    getJson('/api/v1/admin/payments')
        ->assertUnauthorized();
});
