<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\FavoriteLocation;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('can get favorite locations', function (): void {
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    FavoriteLocation::factory()->count(3)->create(['user_id' => $user->id]);

    actingAs($user)
        ->getJson(route('api.v1.rider.favorites.index'))
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'items' => [
                    '*' => [
                        'id',
                        'name',
                        'lat',
                        'lng',
                        'address',
                        'created_at' => ['human', 'string'],
                        'updated_at' => ['human', 'string'],
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

test('favorite locations are paginated', function (): void {
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    FavoriteLocation::factory()->count(15)->create(['user_id' => $user->id]);

    actingAs($user)
        ->getJson(route('api.v1.rider.favorites.index').'?per_page=10')
        ->assertStatus(200)
        ->assertJsonPath('data.pagination.per_page', 10)
        ->assertJsonPath('data.pagination.total', 15);
});

test('only own favorite locations are returned', function (): void {
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
    $otherUser = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    FavoriteLocation::factory()->count(3)->create(['user_id' => $user->id]);
    FavoriteLocation::factory()->count(5)->create(['user_id' => $otherUser->id]);

    actingAs($user)
        ->getJson(route('api.v1.rider.favorites.index'))
        ->assertStatus(200)
        ->assertJsonCount(3, 'data.items');
});

test('favorite locations are ordered by created_at desc', function (): void {
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $first = FavoriteLocation::factory()->create([
        'user_id'    => $user->id,
        'created_at' => now()->subHours(3),
    ]);

    $second = FavoriteLocation::factory()->create([
        'user_id'    => $user->id,
        'created_at' => now()->subHours(2),
    ]);

    $third = FavoriteLocation::factory()->create([
        'user_id'    => $user->id,
        'created_at' => now()->subHours(1),
    ]);

    $response = actingAs($user)
        ->getJson(route('api.v1.rider.favorites.index'))
        ->assertStatus(200);

    $ids = collect($response->json('data.items'))->pluck('id')->toArray();

    expect($ids)->toBe([$third->id, $second->id, $first->id]);
});
