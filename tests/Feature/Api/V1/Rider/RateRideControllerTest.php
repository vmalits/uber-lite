<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('rider can rate their own completed ride', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $data = [
        'rating'  => 5,
        'comment' => 'Great ride!',
    ];

    $response = $this->putJson("/api/v1/rider/rides/{$ride->id}/rating", $data);

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Ride rated successfully.',
            'data'    => [
                'id'     => $ride->id,
                'rating' => [
                    'rating'  => 5,
                    'comment' => 'Great ride!',
                ],
            ],
        ]);

    $this->assertDatabaseHas('ride_ratings', [
        'ride_id'  => $ride->id,
        'rider_id' => $user->id,
        'rating'   => 5,
        'comment'  => 'Great ride!',
    ]);
});

test('rider cannot rate someone else\'s ride', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $otherUser */
    $otherUser = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $otherUser->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $data = [
        'rating' => 4,
    ];

    $response = $this->putJson("/api/v1/rider/rides/{$ride->id}/rating", $data);

    $response->assertForbidden();
});

test('rider cannot rate non-completed ride', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::PENDING,
    ]);

    Sanctum::actingAs($user);

    $data = [
        'rating' => 3,
    ];

    $response = $this->putJson("/api/v1/rider/rides/{$ride->id}/rating", $data);

    $response->assertForbidden();
});

test('guest cannot rate ride', function (): void {
    /** @var Ride $ride */
    $ride = Ride::factory()->create(['status' => RideStatus::COMPLETED]);

    $response = $this->putJson("/api/v1/rider/rides/{$ride->id}/rating", ['rating' => 2]);

    $response->assertUnauthorized();
});

test('rider with incomplete profile cannot rate ride', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->putJson("/api/v1/rider/rides/{$ride->id}/rating", ['rating' => 1]);

    $response->assertForbidden();
});

test('rating validation fails with invalid data', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create(['rider_id' => $user->id, 'status' => RideStatus::COMPLETED]);

    Sanctum::actingAs($user);

    $data = ['rating' => 6, 'comment' => str_repeat('a', 1001)];

    $response = $this->putJson("/api/v1/rider/rides/{$ride->id}/rating", $data);

    $response->assertUnprocessable()->assertJsonValidationErrors(['rating', 'comment']);
});

test('rider cannot rate an already rated ride within 24 hours', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var Ride $ride */
    $ride = Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    // First rating
    $this->actingAs($user)
        ->putJson(
            "/api/v1/rider/rides/{$ride->id}/rating",
            ['rating' => 3, 'comment' => 'Okay'],
        );

    // Second rating within 24 hours
    $response = $this->actingAs($user)
        ->putJson(
            "/api/v1/rider/rides/{$ride->id}/rating",
            ['rating' => 5, 'comment' => 'Better'],
        );

    $response->assertUnprocessable()->assertJsonValidationErrors(['rating']);
});
