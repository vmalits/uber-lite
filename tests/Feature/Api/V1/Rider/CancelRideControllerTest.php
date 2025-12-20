<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('rider can cancel their own ride', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::PENDING,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/cancel");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Ride cancelled successfully.',
            'data'    => [
                'id'     => $ride->id,
                'status' => RideStatus::CANCELLED->value,
            ],
        ]);

    $this->assertDatabaseHas('rides', [
        'id'     => $ride->id,
        'status' => RideStatus::CANCELLED->value,
    ]);
});

test('rider cannot cancel someone else\'s ride', function (): void {
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

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $otherUser->id,
        'status'   => RideStatus::PENDING,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/cancel");

    $response->assertForbidden();
});

test('rider cannot cancel completed ride', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/cancel");

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['ride']);
});

test('guest cannot cancel ride', function (): void {
    /** @var Ride $ride */
    $ride = Ride::factory()->create();

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/cancel");

    $response->assertUnauthorized();
});

test('rider with incomplete profile cannot cancel ride', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/cancel");

    $response->assertForbidden();
});
