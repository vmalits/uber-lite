<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Admin;

use App\Enums\PayoutStatus;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\DriverPayout;
use App\Models\Ride;
use App\Models\RideRating;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

function createAdminForDriverEarnings(): User
{
    return User::factory()->create([
        'role'              => UserRole::ADMIN,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
}

test('admin can get driver earnings', function (): void {
    $admin = createAdminForDriverEarnings();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);

    Ride::factory()->count(3)->completed()->create([
        'driver_id' => $driver->id,
        'price'     => 1000,
    ]);

    actingAs($admin)
        ->getJson("/api/v1/admin/drivers/{$driver->id}/earnings")
        ->assertOk()
        ->assertJsonPath('data.completed_rides', 3)
        ->assertJsonPath('data.total_earned', 3000)
        ->assertJsonPath('data.average_earnings_per_ride', 1000);
});

test('admin driver earnings includes stats from completed and cancelled rides', function (): void {
    $admin = createAdminForDriverEarnings();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);

    Ride::factory()->count(4)->completed()->create(['driver_id' => $driver->id, 'price' => 500]);
    Ride::factory()->count(1)->cancelled()->create(['driver_id' => $driver->id]);

    actingAs($admin)
        ->getJson("/api/v1/admin/drivers/{$driver->id}/earnings")
        ->assertOk()
        ->assertJsonPath('data.total_rides', 5)
        ->assertJsonPath('data.completed_rides', 4)
        ->assertJsonPath('data.cancelled_rides', 1)
        ->assertJsonPath('data.completion_rate', 80)
        ->assertJsonPath('data.total_earned', 2000);
});

test('admin driver earnings includes average rating', function (): void {
    $admin = createAdminForDriverEarnings();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);

    $ride1 = Ride::factory()->completed()->create(['driver_id' => $driver->id]);
    $ride2 = Ride::factory()->completed()->create(['driver_id' => $driver->id]);

    RideRating::factory()->create(['ride_id' => $ride1->id, 'rider_id' => $ride1->rider_id, 'rating' => 4]);
    RideRating::factory()->create(['ride_id' => $ride2->id, 'rider_id' => $ride2->rider_id, 'rating' => 5]);

    actingAs($admin)
        ->getJson("/api/v1/admin/drivers/{$driver->id}/earnings")
        ->assertOk()
        ->assertJsonPath('data.average_rating', 4.5);
});

test('admin driver earnings includes payout totals', function (): void {
    $admin = createAdminForDriverEarnings();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);

    DriverPayout::factory()->create(['driver_id' => $driver->id, 'amount' => 5000, 'status' => PayoutStatus::COMPLETED]);
    DriverPayout::factory()->create(['driver_id' => $driver->id, 'amount' => 3000, 'status' => PayoutStatus::PENDING]);
    DriverPayout::factory()->create(['driver_id' => $driver->id, 'amount' => 2000, 'status' => PayoutStatus::APPROVED]);

    actingAs($admin)
        ->getJson("/api/v1/admin/drivers/{$driver->id}/earnings")
        ->assertOk()
        ->assertJsonPath('data.total_payouts', 5000)
        ->assertJsonPath('data.pending_payouts', 5000);
});

test('admin driver earnings includes daily breakdown', function (): void {
    $admin = createAdminForDriverEarnings();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);

    Ride::factory()->completed()->create([
        'driver_id'    => $driver->id,
        'price'        => 600,
        'completed_at' => now()->subDay(),
        'created_at'   => now()->subDay(),
    ]);
    Ride::factory()->completed()->create([
        'driver_id'    => $driver->id,
        'price'        => 400,
        'completed_at' => now()->subDay(),
        'created_at'   => now()->subDay(),
    ]);

    actingAs($admin)
        ->getJson("/api/v1/admin/drivers/{$driver->id}/earnings")
        ->assertOk()
        ->assertJsonCount(1, 'data.daily');

    $day = now()->subDay()->toDateString();
    actingAs($admin)
        ->getJson("/api/v1/admin/drivers/{$driver->id}/earnings")
        ->assertJsonPath('data.daily.0.date', $day)
        ->assertJsonPath('data.daily.0.total_rides', 2)
        ->assertJsonPath('data.daily.0.total_earnings', 1000);
});

test('admin driver earnings respects from and to date filters', function (): void {
    $admin = createAdminForDriverEarnings();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);

    Ride::factory()->completed()->create([
        'driver_id'    => $driver->id,
        'price'        => 500,
        'completed_at' => now()->subDays(10),
        'created_at'   => now()->subDays(10),
    ]);
    Ride::factory()->completed()->create([
        'driver_id'    => $driver->id,
        'price'        => 300,
        'completed_at' => now()->subDays(2),
        'created_at'   => now()->subDays(2),
    ]);

    $from = now()->subDays(5)->toDateString();
    $to = now()->toDateString();

    actingAs($admin)
        ->getJson("/api/v1/admin/drivers/{$driver->id}/earnings?from={$from}&to={$to}")
        ->assertOk()
        ->assertJsonCount(1, 'data.daily')
        ->assertJsonPath('data.daily.0.total_earnings', 300);
});

test('admin driver earnings returns zeros for driver with no rides', function (): void {
    $admin = createAdminForDriverEarnings();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);

    actingAs($admin)
        ->getJson("/api/v1/admin/drivers/{$driver->id}/earnings")
        ->assertOk()
        ->assertJsonPath('data.total_rides', 0)
        ->assertJsonPath('data.completed_rides', 0)
        ->assertJsonPath('data.cancelled_rides', 0)
        ->assertJsonPath('data.total_earned', 0)
        ->assertJsonPath('data.average_rating', 0)
        ->assertJsonPath('data.total_payouts', 0)
        ->assertJsonPath('data.pending_payouts', 0)
        ->assertJsonCount(0, 'data.daily');
});

test('admin driver earnings response has correct structure', function (): void {
    $admin = createAdminForDriverEarnings();
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);

    Ride::factory()->completed()->create(['driver_id' => $driver->id]);

    actingAs($admin)
        ->getJson("/api/v1/admin/drivers/{$driver->id}/earnings")
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'total_earned',
                'total_rides',
                'completed_rides',
                'cancelled_rides',
                'completion_rate',
                'average_earnings_per_ride',
                'average_rating',
                'total_payouts',
                'pending_payouts',
                'daily',
            ],
        ]);
});

test('non-admin cannot get driver earnings', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);

    actingAs($rider)
        ->getJson("/api/v1/admin/drivers/{$driver->id}/earnings")
        ->assertForbidden();
});

test('unauthenticated user cannot get driver earnings', function (): void {
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);

    getJson("/api/v1/admin/drivers/{$driver->id}/earnings")
        ->assertUnauthorized();
});

test('admin driver earnings returns 404 for non-existent driver', function (): void {
    $admin = createAdminForDriverEarnings();

    actingAs($admin)
        ->getJson('/api/v1/admin/drivers/nonexistent/earnings')
        ->assertNotFound();
});
