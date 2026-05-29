<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\RideTip;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('rider can get tip history', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    RideTip::factory()->create([
        'rider_id' => $rider->id,
        'amount'   => 500,
    ]);

    actingAs($rider)
        ->getJson('/api/v1/rider/tips')
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'success',
            'data' => [
                'items' => [
                    '*' => [
                        'id',
                        'ride_id',
                        'driver_id',
                        'amount',
                        'comment',
                        'created_at' => ['human', 'string'],
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

test('rider sees only own tips', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $otherRider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    RideTip::factory()->create([
        'rider_id' => $rider->id,
        'amount'   => 1000,
    ]);

    RideTip::factory()->create([
        'rider_id' => $otherRider->id,
        'amount'   => 2000,
    ]);

    actingAs($rider)
        ->getJson('/api/v1/rider/tips')
        ->assertStatus(200)
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.amount', 1000);
});

test('rider can filter tips by date range', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    RideTip::factory()->create([
        'rider_id'   => $rider->id,
        'amount'     => 500,
        'created_at' => now()->subDays(5),
    ]);

    RideTip::factory()->create([
        'rider_id'   => $rider->id,
        'amount'     => 1000,
        'created_at' => now()->subDays(10),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/rider/tips?from='.now()->subDays(7)->format('Y-m-d').'&to='.now()->format('Y-m-d'))
        ->assertStatus(200)
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.amount', 500);
});

test('tips are ordered by created_at desc', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $first = RideTip::factory()->create([
        'rider_id'   => $rider->id,
        'amount'     => 100,
        'created_at' => now()->subDays(2),
    ]);

    $second = RideTip::factory()->create([
        'rider_id'   => $rider->id,
        'amount'     => 200,
        'created_at' => now()->subDays(1),
    ]);

    $response = actingAs($rider)
        ->getJson('/api/v1/rider/tips')
        ->assertStatus(200);

    $amounts = collect($response->json('data.items'))->pluck('amount')->toArray();

    expect($amounts)->toBe([200, 100]);
});

test('tips are paginated', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    RideTip::factory()->count(15)->create(['rider_id' => $rider->id]);

    actingAs($rider)
        ->getJson('/api/v1/rider/tips?per_page=10')
        ->assertStatus(200)
        ->assertJsonPath('data.pagination.per_page', 10)
        ->assertJsonPath('data.pagination.total', 15)
        ->assertJsonCount(10, 'data.items');
});

test('empty tips returns empty items', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/rider/tips')
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonCount(0, 'data.items');
});

test('unauthenticated user cannot access rider tips', function (): void {
    getJson('/api/v1/rider/tips')
        ->assertStatus(401);
});

test('driver cannot access rider tips', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($driver)
        ->getJson('/api/v1/rider/tips')
        ->assertStatus(403);
});
