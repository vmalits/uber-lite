<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\FavoriteLocation;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('can get a single favorite location', function (): void {
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $favorite = FavoriteLocation::factory()->create(['user_id' => $user->id]);

    actingAs($user)
        ->getJson(route('api.v1.rider.favorites.show', $favorite->id))
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.id', $favorite->id)
        ->assertJsonPath('data.name', $favorite->name)
        ->assertJsonPath('data.lat', $favorite->lat)
        ->assertJsonPath('data.lng', $favorite->lng)
        ->assertJsonPath('data.address', $favorite->address);
});

test('cannot get favorite location from another user', function (): void {
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

    $favorite = FavoriteLocation::factory()->create(['user_id' => $otherUser->id]);

    actingAs($user)
        ->getJson(route('api.v1.rider.favorites.show', $favorite->id))
        ->assertStatus(403);
});

test('returns 404 for non-existent favorite location', function (): void {
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($user)
        ->getJson(route('api.v1.rider.favorites.show', 'non-existent-id'))
        ->assertStatus(404);
});

test('unauthenticated user cannot get favorite location', function (): void {
    $favorite = FavoriteLocation::factory()->create();

    $this->getJson(route('api.v1.rider.favorites.show', $favorite->id))
        ->assertStatus(401);
});

test('driver role cannot access rider favorites', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $favorite = FavoriteLocation::factory()->create(['user_id' => $rider->id]);

    actingAs($driver)
        ->getJson(route('api.v1.rider.favorites.show', $favorite->id))
        ->assertStatus(403);
});
