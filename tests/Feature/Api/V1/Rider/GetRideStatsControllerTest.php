<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('rider can get their ride statistics', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::COMPLETED,
        'price'    => 100.00,
    ]);
    Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::COMPLETED,
        'price'    => 150.00,
    ]);
    Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::CANCELLED,
        'price'    => 50.00,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/stats');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'total_rides',
                'completed_rides',
                'cancelled_rides',
                'completion_rate',
                'average_price',
                'total_spent',
            ],
        ])
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.total_rides', 3)
        ->assertJsonPath('data.completed_rides', 2)
        ->assertJsonPath('data.cancelled_rides', 1)
        ->assertJsonPath('data.completion_rate', 66.67)
        ->assertJsonPath('data.average_price', 100)
        ->assertJsonPath('data.total_spent', 300);
});

test('rider with no rides gets zero statistics', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/stats');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.total_rides', 0)
        ->assertJsonPath('data.completed_rides', 0)
        ->assertJsonPath('data.cancelled_rides', 0)
        ->assertJsonPath('data.completion_rate', 0)
        ->assertJsonPath('data.average_price', 0)
        ->assertJsonPath('data.total_spent', 0);
});

test('guest cannot get ride statistics', function (): void {
    $response = $this->getJson('/api/v1/rider/stats');

    $response->assertUnauthorized();
});

test('driver cannot get ride statistics', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/stats');

    $response->assertForbidden();
});

test('rider with incomplete profile cannot get ride statistics', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/stats');

    $response->assertForbidden();
});

test('rider statistics calculation is accurate with mixed ride statuses', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    // Create rides with different statuses
    Ride::factory()->create(['rider_id' => $user->id, 'status' => RideStatus::COMPLETED, 'price' => 50.00]);
    Ride::factory()->create(['rider_id' => $user->id, 'status' => RideStatus::COMPLETED, 'price' => 75.00]);
    Ride::factory()->create(['rider_id' => $user->id, 'status' => RideStatus::COMPLETED, 'price' => 125.00]);
    Ride::factory()->create(['rider_id' => $user->id, 'status' => RideStatus::CANCELLED, 'price' => 25.00]);
    Ride::factory()->create(['rider_id' => $user->id, 'status' => RideStatus::CANCELLED, 'price' => 35.00]);
    Ride::factory()->create(['rider_id' => $user->id, 'status' => RideStatus::PENDING, 'price' => 60.00]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/rider/stats');

    $response->assertOk()
        ->assertJsonPath('data.total_rides', 6)
        ->assertJsonPath('data.completed_rides', 3)
        ->assertJsonPath('data.cancelled_rides', 2)
        ->assertJsonPath('data.completion_rate', 50)
        ->assertJsonPath('data.average_price', 61.67)
        ->assertJsonPath('data.total_spent', 370);
});

test('rider statistics only include their own rides', function (): void {
    /** @var User $rider1 */
    $rider1 = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $rider2 */
    $rider2 = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    // Create rides for both riders
    Ride::factory()->create(['rider_id' => $rider1->id, 'status' => RideStatus::COMPLETED, 'price' => 100.00]);
    Ride::factory()->create(['rider_id' => $rider2->id, 'status' => RideStatus::COMPLETED, 'price' => 200.00]);
    Ride::factory()->create(['rider_id' => $rider1->id, 'status' => RideStatus::CANCELLED, 'price' => 50.00]);

    Sanctum::actingAs($rider1);

    $response = $this->getJson('/api/v1/rider/stats');

    $response->assertOk()
        ->assertJsonPath('data.total_rides', 2)
        ->assertJsonPath('data.completed_rides', 1)
        ->assertJsonPath('data.cancelled_rides', 1)
        ->assertJsonPath('data.completion_rate', 50)
        ->assertJsonPath('data.average_price', 75)
        ->assertJsonPath('data.total_spent', 150);
});
