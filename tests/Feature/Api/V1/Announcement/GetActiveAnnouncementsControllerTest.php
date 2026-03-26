<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Announcement;

use App\Enums\AnnouncementTarget;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\Announcement;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

function createRider(): User
{
    return User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
}

function createDriver(): User
{
    return User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
}

function createAdmin(): User
{
    return User::factory()->create([
        'role'              => UserRole::ADMIN,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
}

test('rider can see active announcements targeted to all', function (): void {
    $rider = createRider();
    $admin = createAdmin();
    Announcement::factory()->create([
        'admin_id'     => $admin->id,
        'target'       => AnnouncementTarget::ALL,
        'is_active'    => true,
        'published_at' => now()->subHour(),
        'expires_at'   => now()->addDays(7),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/announcements')
        ->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.target', 'all');
});

test('rider can see announcements targeted to riders', function (): void {
    $rider = createRider();
    $admin = createAdmin();
    Announcement::factory()->create([
        'admin_id'     => $admin->id,
        'target'       => AnnouncementTarget::RIDERS,
        'is_active'    => true,
        'published_at' => now()->subHour(),
        'expires_at'   => now()->addDays(7),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/announcements')
        ->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.target', 'riders');
});

test('rider cannot see announcements targeted to drivers', function (): void {
    $rider = createRider();
    $admin = createAdmin();
    Announcement::factory()->create([
        'admin_id'     => $admin->id,
        'target'       => AnnouncementTarget::DRIVERS,
        'is_active'    => true,
        'published_at' => now()->subHour(),
        'expires_at'   => now()->addDays(7),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/announcements')
        ->assertOk()
        ->assertJsonCount(0, 'data.items');
});

test('driver can see announcements targeted to drivers', function (): void {
    $driver = createDriver();
    $admin = createAdmin();
    Announcement::factory()->create([
        'admin_id'     => $admin->id,
        'target'       => AnnouncementTarget::DRIVERS,
        'is_active'    => true,
        'published_at' => now()->subHour(),
        'expires_at'   => now()->addDays(7),
    ]);

    actingAs($driver)
        ->getJson('/api/v1/announcements')
        ->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.target', 'drivers');
});

test('driver cannot see announcements targeted to riders', function (): void {
    $driver = createDriver();
    $admin = createAdmin();
    Announcement::factory()->create([
        'admin_id'     => $admin->id,
        'target'       => AnnouncementTarget::RIDERS,
        'is_active'    => true,
        'published_at' => now()->subHour(),
        'expires_at'   => now()->addDays(7),
    ]);

    actingAs($driver)
        ->getJson('/api/v1/announcements')
        ->assertOk()
        ->assertJsonCount(0, 'data.items');
});

test('inactive announcements are hidden', function (): void {
    $rider = createRider();
    $admin = createAdmin();
    Announcement::factory()->create([
        'admin_id'     => $admin->id,
        'target'       => AnnouncementTarget::ALL,
        'is_active'    => false,
        'published_at' => now()->subHour(),
        'expires_at'   => now()->addDays(7),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/announcements')
        ->assertOk()
        ->assertJsonCount(0, 'data.items');
});

test('announcements not yet published are hidden', function (): void {
    $rider = createRider();
    $admin = createAdmin();
    Announcement::factory()->create([
        'admin_id'     => $admin->id,
        'target'       => AnnouncementTarget::ALL,
        'is_active'    => true,
        'published_at' => now()->addDay(),
        'expires_at'   => now()->addDays(7),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/announcements')
        ->assertOk()
        ->assertJsonCount(0, 'data.items');
});

test('expired announcements are hidden', function (): void {
    $rider = createRider();
    $admin = createAdmin();
    Announcement::factory()->create([
        'admin_id'     => $admin->id,
        'target'       => AnnouncementTarget::ALL,
        'is_active'    => true,
        'published_at' => now()->subDays(30),
        'expires_at'   => now()->subDay(),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/announcements')
        ->assertOk()
        ->assertJsonCount(0, 'data.items');
});

test('announcement without published_at is visible', function (): void {
    $rider = createRider();
    $admin = createAdmin();
    Announcement::factory()->create([
        'admin_id'     => $admin->id,
        'target'       => AnnouncementTarget::ALL,
        'is_active'    => true,
        'published_at' => null,
        'expires_at'   => null,
    ]);

    actingAs($rider)
        ->getJson('/api/v1/announcements')
        ->assertOk()
        ->assertJsonCount(1, 'data.items');
});

test('announcement without expires_at is visible', function (): void {
    $rider = createRider();
    $admin = createAdmin();
    Announcement::factory()->create([
        'admin_id'     => $admin->id,
        'target'       => AnnouncementTarget::ALL,
        'is_active'    => true,
        'published_at' => now()->subHour(),
        'expires_at'   => null,
    ]);

    actingAs($rider)
        ->getJson('/api/v1/announcements')
        ->assertOk()
        ->assertJsonCount(1, 'data.items');
});

test('soft deleted announcements are hidden', function (): void {
    $rider = createRider();
    $admin = createAdmin();
    $announcement = Announcement::factory()->create([
        'admin_id'     => $admin->id,
        'target'       => AnnouncementTarget::ALL,
        'is_active'    => true,
        'published_at' => now()->subHour(),
        'expires_at'   => now()->addDays(7),
    ]);

    $announcement->delete();

    actingAs($rider)
        ->getJson('/api/v1/announcements')
        ->assertOk()
        ->assertJsonCount(0, 'data.items');
});

test('response has correct json structure', function (): void {
    $rider = createRider();
    $admin = createAdmin();
    Announcement::factory()->create([
        'admin_id'     => $admin->id,
        'target'       => AnnouncementTarget::ALL,
        'is_active'    => true,
        'published_at' => now()->subHour(),
        'expires_at'   => now()->addDays(7),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/announcements')
        ->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'items' => [
                    '*' => ['id', 'title', 'body', 'target', 'is_active', 'published_at', 'expires_at', 'created_at', 'updated_at'],
                ],
                'pagination' => ['total', 'per_page', 'current_page', 'last_page'],
            ],
        ]);
});

test('unauthenticated user cannot access announcements', function (): void {
    getJson('/api/v1/announcements')
        ->assertUnauthorized();
});

test('rider and driver see combined all + role-specific announcements', function (): void {
    $rider = createRider();
    $admin = createAdmin();

    Announcement::factory()->create([
        'admin_id'     => $admin->id,
        'target'       => AnnouncementTarget::ALL,
        'is_active'    => true,
        'published_at' => now()->subHour(),
        'title'        => 'For Everyone',
    ]);
    Announcement::factory()->create([
        'admin_id'     => $admin->id,
        'target'       => AnnouncementTarget::RIDERS,
        'is_active'    => true,
        'published_at' => now()->subHour(),
        'title'        => 'For Riders Only',
    ]);
    Announcement::factory()->create([
        'admin_id'     => $admin->id,
        'target'       => AnnouncementTarget::DRIVERS,
        'is_active'    => true,
        'published_at' => now()->subHour(),
        'title'        => 'For Drivers Only',
    ]);

    actingAs($rider)
        ->getJson('/api/v1/announcements')
        ->assertOk()
        ->assertJsonCount(2, 'data.items');
});
