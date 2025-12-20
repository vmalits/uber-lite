<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('allows rider to get their own ride status', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data'    => [
                'id'       => $ride->id,
                'rider_id' => $user->id,
                'status'   => $ride->status->value,
            ],
        ]);
});

test('denies rider to get another rider ride status', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $otherUser */
    $otherUser = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $otherUser->id,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}");

    $response->assertForbidden()
        ->assertJsonPath('message', 'This action is unauthorized.');
});

test('returns 404 for non-existent ride', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/rides/01jk9v6v9v6v9v6v9v6v9v6v9v');

    $response->assertNotFound();
});

test('denies access if profile not completed', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}");

    $response->assertForbidden()
        ->assertJsonPath('message', 'Forbidden. Profile step not completed.');
});
