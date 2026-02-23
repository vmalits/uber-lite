<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('driver can get daily earnings', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    Ride::factory()->count(3)->create([
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 1000,
        'completed_at' => now(),
    ]);

    actingAs($driver)
        ->getJson('/api/v1/driver/earnings/daily')
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'date',
                    'total_rides',
                    'total_earnings',
                    'average_per_ride',
                    'online_minutes',
                ],
            ],
        ]);
});

test('driver can filter earnings by date range', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    Ride::factory()->create([
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 1000,
        'completed_at' => now()->subDays(5),
    ]);

    Ride::factory()->create([
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 2000,
        'completed_at' => now()->subDays(10),
    ]);

    actingAs($driver)
        ->getJson('/api/v1/driver/earnings/daily?from='.now()->subDays(7)->format('Y-m-d').'&to='.now()->format('Y-m-d'))
        ->assertStatus(200)
        ->assertJsonPath('success', true);
});

test('earnings include only completed rides', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    Ride::factory()->create([
        'driver_id'    => $driver->id,
        'status'       => RideStatus::COMPLETED,
        'price'        => 1000,
        'completed_at' => now(),
    ]);

    Ride::factory()->create([
        'driver_id'    => $driver->id,
        'status'       => RideStatus::CANCELLED,
        'price'        => null,
        'completed_at' => null,
    ]);

    $response = actingAs($driver)
        ->getJson('/api/v1/driver/earnings/daily')
        ->assertStatus(200);

    $data = $response->json('data');
    expect($data[0]['total_rides'])->toBe(1)
        ->and($data[0]['total_earnings'])->toBe(1000);
});

test('unauthenticated user cannot access earnings', function (): void {
    getJson('/api/v1/driver/earnings/daily')
        ->assertStatus(401);
});

test('rider cannot access driver earnings', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/driver/earnings/daily')
        ->assertStatus(403);
});

test('empty earnings returns empty array', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($driver)
        ->getJson('/api/v1/driver/earnings/daily')
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonCount(0, 'data');
});
