<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\FavoriteLocation;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $user->phone_verified_at = now();
    $user->email_verified_at = now();
    $user->save();
});

test('can delete favorite location', function (): void {
    $user = User::first();
    $favorite = FavoriteLocation::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->deleteJson('/api/v1/rider/favorites/'.$favorite->id);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'message',
        ]);

    $this->assertDatabaseMissing('favorite_locations', ['id' => $favorite->id]);
});

test('cannot delete other user favorite location', function (): void {
    $user = User::first();
    $otherUser = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $otherFavorite = FavoriteLocation::factory()->create(['user_id' => $otherUser->id]);

    Sanctum::actingAs($user);

    $this->deleteJson('/api/v1/rider/favorites/'.$otherFavorite->id)
        ->assertStatus(403);
});

test('cannot delete non-existent favorite location', function (): void {
    $user = User::first();

    Sanctum::actingAs($user);

    $nonExistentId = '01kf6py04q6t14aawf6gy5thb3';

    $this->deleteJson('/api/v1/rider/favorites/'.$nonExistentId)
        ->assertStatus(404);
});

test('guest cannot delete favorite location', function (): void {
    $favorite = FavoriteLocation::factory()->create();

    $this->deleteJson('/api/v1/rider/favorites/'.$favorite->id)
        ->assertStatus(401);
});
