<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\RideRating;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('driver can get their ride statistics', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride1 = Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
        'price'     => 100.00,
    ]);

    $ride2 = Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
        'price'     => 150.00,
    ]);

    $ride3 = Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::CANCELLED,
        'price'     => 50.00,
    ]);

    RideRating::factory()->forRide($ride1)->create(['rating' => 5]);
    RideRating::factory()->forRide($ride2)->create(['rating' => 4]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/stats');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'total_rides',
                'completed_rides',
                'cancelled_rides',
                'completion_rate',
                'average_rating',
                'average_earnings_per_ride',
                'total_earned',
            ],
        ])
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.total_rides', 3)
        ->assertJsonPath('data.completed_rides', 2)
        ->assertJsonPath('data.cancelled_rides', 1)
        ->assertJsonPath('data.completion_rate', 66.67)
        ->assertJsonPath('data.average_rating', 4.5)
        ->assertJsonPath('data.average_earnings_per_ride', 125)
        ->assertJsonPath('data.total_earned', 250);
});

test('driver with no rides gets zero statistics', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/stats');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.total_rides', 0)
        ->assertJsonPath('data.completed_rides', 0)
        ->assertJsonPath('data.cancelled_rides', 0)
        ->assertJsonPath('data.completion_rate', 0)
        ->assertJsonPath('data.average_rating', 0)
        ->assertJsonPath('data.average_earnings_per_ride', 0)
        ->assertJsonPath('data.total_earned', 0);
});

test('guest cannot get driver statistics', function (): void {
    $response = $this->getJson('/api/v1/driver/stats');

    $response->assertUnauthorized();
});

test('rider cannot get driver statistics', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/driver/stats');

    $response->assertForbidden();
});

test('driver with incomplete profile cannot get statistics', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/stats');

    $response->assertForbidden();
});

test('driver statistics calculation is accurate with mixed ride statuses', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    // Create rides with different statuses
    $ride1 = Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
        'price'     => 50.00,
    ]);
    $ride2 = Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
        'price'     => 75.00,
    ]);
    $ride3 = Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
        'price'     => 125.00,
    ]);
    Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::CANCELLED,
        'price'     => 25.00,
    ]);
    Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::CANCELLED,
        'price'     => 35.00,
    ]);
    Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::PENDING,
        'price'     => 60.00,
    ]);

    RideRating::factory()->forRide($ride1)->create(['rating' => 5]);
    RideRating::factory()->forRide($ride2)->create(['rating' => 4]);
    RideRating::factory()->forRide($ride3)->create(['rating' => 5]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/stats');

    $response->assertOk()
        ->assertJsonPath('data.total_rides', 6)
        ->assertJsonPath('data.completed_rides', 3)
        ->assertJsonPath('data.cancelled_rides', 2)
        ->assertJsonPath('data.completion_rate', 50)
        ->assertJsonPath('data.average_rating', 4.67)
        ->assertJsonPath('data.average_earnings_per_ride', 83.33)
        ->assertJsonPath('data.total_earned', 250);
});

test('driver statistics only include their own rides', function (): void {
    /** @var User $driver1 */
    $driver1 = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $driver2 */
    $driver2 = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    // Create rides for both drivers
    $ride1 = Ride::factory()->create([
        'driver_id' => $driver1->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
        'price'     => 100.00,
    ]);
    Ride::factory()->create([
        'driver_id' => $driver2->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
        'price'     => 200.00,
    ]);
    Ride::factory()->create([
        'driver_id' => $driver1->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::CANCELLED,
        'price'     => 50.00,
    ]);

    RideRating::factory()->forRide($ride1)->create(['rating' => 5]);

    Sanctum::actingAs($driver1);

    $response = $this->getJson('/api/v1/driver/stats');

    $response->assertOk()
        ->assertJsonPath('data.total_rides', 2)
        ->assertJsonPath('data.completed_rides', 1)
        ->assertJsonPath('data.cancelled_rides', 1)
        ->assertJsonPath('data.completion_rate', 50)
        ->assertJsonPath('data.average_rating', 5)
        ->assertJsonPath('data.average_earnings_per_ride', 100)
        ->assertJsonPath('data.total_earned', 100);
});

test('driver statistics with no ratings returns zero average rating', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Ride::factory()->create([
        'driver_id' => $driver->id,
        'rider_id'  => $rider->id,
        'status'    => RideStatus::COMPLETED,
        'price'     => 100.00,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/stats');

    $response->assertOk()
        ->assertJsonPath('data.average_rating', 0);
});
