<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('rider can get their ride history', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    // Create some rides for this user
    Ride::factory()->count(3)->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    // Create a ride for another user
    Ride::factory()->create([
        'status' => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/rides/history');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'items',
                'pagination' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                ],
            ],
        ])
        ->assertJsonCount(3, 'data.items')
        ->assertJsonPath('data.pagination.total', 3);
});

test('rider history is paginated', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Ride::factory()->count(20)->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/rides/history?per_page=10');

    $response->assertOk()
        ->assertJsonCount(10, 'data.items')
        ->assertJsonPath('data.pagination.total', 20)
        ->assertJsonPath('data.pagination.per_page', 10)
        ->assertJsonPath('data.pagination.last_page', 2);
});

test('guest cannot get ride history', function (): void {
    $response = $this->getJson('/api/v1/rider/rides/history');

    $response->assertUnauthorized();
});

test('rider with incomplete profile cannot get ride history', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/rides/history');

    $response->assertForbidden();
});

test('rider can filter ride history by status', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::CANCELLED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/rides/history?filter[status]=completed');

    $response->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.status', RideStatus::COMPLETED->value);
});

test('rider can sort ride history by price', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Ride::factory()->create([
        'rider_id'   => $user->id,
        'status'     => RideStatus::COMPLETED,
        'price'      => 100.0,
        'created_at' => now()->subDay(),
    ]);

    Ride::factory()->create([
        'rider_id'   => $user->id,
        'status'     => RideStatus::COMPLETED,
        'price'      => 200.0,
        'created_at' => now(),
    ]);

    Sanctum::actingAs($user);

    // Sort by price ascending
    $response = $this->getJson('/api/v1/rider/rides/history?sort=price');

    $response->assertOk()
        ->assertJsonPath('data.items.0.price', 100)
        ->assertJsonPath('data.items.1.price', 200);

    // Sort by price descending
    $response = $this->getJson('/api/v1/rider/rides/history?sort=-price');

    $response->assertOk()
        ->assertJsonPath('data.items.0.price', 200)
        ->assertJsonPath('data.items.1.price', 100);
});
