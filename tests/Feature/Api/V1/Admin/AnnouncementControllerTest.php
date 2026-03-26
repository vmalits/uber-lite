<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Admin;

use App\Enums\AnnouncementTarget;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\Announcement;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

function createAdmin(): User
{
    return User::factory()->create([
        'role'              => UserRole::ADMIN,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
}

function validPayload(): array
{
    return [
        'title'        => 'New Feature Launch',
        'body'         => 'We are excited to announce a new ride scheduling feature!',
        'target'       => AnnouncementTarget::ALL->value,
        'is_active'    => true,
        'published_at' => now()->toDateTimeString(),
        'expires_at'   => now()->addDays(30)->toDateTimeString(),
    ];
}

test('admin can create announcement', function (): void {
    $admin = createAdmin();

    actingAs($admin)
        ->postJson('/api/v1/admin/announcements', validPayload())
        ->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.title', 'New Feature Launch')
        ->assertJsonPath('data.body', 'We are excited to announce a new ride scheduling feature!')
        ->assertJsonPath('data.target', 'all')
        ->assertJsonPath('data.is_active', true);
});

test('admin can create announcement with riders target', function (): void {
    $admin = createAdmin();
    $payload = validPayload();
    $payload['target'] = AnnouncementTarget::RIDERS->value;

    actingAs($admin)
        ->postJson('/api/v1/admin/announcements', $payload)
        ->assertCreated()
        ->assertJsonPath('data.target', 'riders');
});

test('admin can create announcement with drivers target', function (): void {
    $admin = createAdmin();
    $payload = validPayload();
    $payload['target'] = AnnouncementTarget::DRIVERS->value;

    actingAs($admin)
        ->postJson('/api/v1/admin/announcements', $payload)
        ->assertCreated()
        ->assertJsonPath('data.target', 'drivers');
});

test('admin can create announcement without dates', function (): void {
    $admin = createAdmin();
    $payload = validPayload();
    unset($payload['published_at'], $payload['expires_at']);

    actingAs($admin)
        ->postJson('/api/v1/admin/announcements', $payload)
        ->assertCreated()
        ->assertJsonPath('data.published_at', null)
        ->assertJsonPath('data.expires_at', null);
});

test('title is required', function (): void {
    $admin = createAdmin();
    $payload = validPayload();
    unset($payload['title']);

    actingAs($admin)
        ->postJson('/api/v1/admin/announcements', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title']);
});

test('body is required', function (): void {
    $admin = createAdmin();
    $payload = validPayload();
    unset($payload['body']);

    actingAs($admin)
        ->postJson('/api/v1/admin/announcements', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['body']);
});

test('target must be valid enum', function (): void {
    $admin = createAdmin();
    $payload = validPayload();
    $payload['target'] = 'invalid';

    actingAs($admin)
        ->postJson('/api/v1/admin/announcements', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['target']);
});

test('non-admin cannot create announcement', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->postJson('/api/v1/admin/announcements', validPayload())
        ->assertForbidden();
});

test('unauthenticated user cannot create announcement', function (): void {
    postJson('/api/v1/admin/announcements', validPayload())
        ->assertUnauthorized();
});

test('admin can list announcements', function (): void {
    $admin = createAdmin();
    Announcement::factory()->count(3)->create(['admin_id' => $admin->id]);

    actingAs($admin)
        ->getJson('/api/v1/admin/announcements')
        ->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'items' => [
                    '*' => ['id', 'title', 'body', 'target', 'is_active', 'published_at', 'expires_at', 'created_at', 'updated_at'],
                ],
                'pagination' => ['total', 'per_page', 'current_page', 'last_page'],
            ],
        ])
        ->assertJsonCount(3, 'data.items');
});

test('admin announcements list is empty initially', function (): void {
    $admin = createAdmin();

    actingAs($admin)
        ->getJson('/api/v1/admin/announcements')
        ->assertOk()
        ->assertJsonCount(0, 'data.items');
});

test('non-admin cannot list announcements', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/admin/announcements')
        ->assertForbidden();
});

test('admin can update announcement', function (): void {
    $admin = createAdmin();
    $announcement = Announcement::factory()->create([
        'admin_id' => $admin->id,
        'title'    => 'Old Title',
    ]);

    actingAs($admin)
        ->putJson("/api/v1/admin/announcements/{$announcement->id}", [
            'title'     => 'Updated Title',
            'body'      => 'Updated body content.',
            'target'    => AnnouncementTarget::RIDERS->value,
            'is_active' => false,
        ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.title', 'Updated Title')
        ->assertJsonPath('data.body', 'Updated body content.')
        ->assertJsonPath('data.target', 'riders')
        ->assertJsonPath('data.is_active', false);
});

test('non-admin cannot update announcement', function (): void {
    $admin = createAdmin();
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
    $announcement = Announcement::factory()->create(['admin_id' => $admin->id]);

    actingAs($rider)
        ->putJson("/api/v1/admin/announcements/{$announcement->id}", validPayload())
        ->assertForbidden();
});

test('admin can soft delete announcement', function (): void {
    $admin = createAdmin();
    $announcement = Announcement::factory()->create(['admin_id' => $admin->id]);

    actingAs($admin)
        ->deleteJson("/api/v1/admin/announcements/{$announcement->id}")
        ->assertOk()
        ->assertJsonPath('success', true);

    expect(Announcement::withTrashed()->where('id', $announcement->id)->exists())->toBeTrue();
    expect(Announcement::where('id', $announcement->id)->exists())->toBeFalse();
});

test('non-admin cannot delete announcement', function (): void {
    $admin = createAdmin();
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
    $announcement = Announcement::factory()->create(['admin_id' => $admin->id]);

    actingAs($rider)
        ->deleteJson("/api/v1/admin/announcements/{$announcement->id}")
        ->assertForbidden();
});

test('admin can filter announcements by target', function (): void {
    $admin = createAdmin();
    Announcement::factory()->create(['admin_id' => $admin->id, 'target' => AnnouncementTarget::ALL]);
    Announcement::factory()->create(['admin_id' => $admin->id, 'target' => AnnouncementTarget::RIDERS]);

    actingAs($admin)
        ->getJson('/api/v1/admin/announcements?filter[target]=riders')
        ->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.target', 'riders');
});

test('admin can filter announcements by is_active', function (): void {
    $admin = createAdmin();
    Announcement::factory()->create(['admin_id' => $admin->id, 'is_active' => true]);
    Announcement::factory()->create(['admin_id' => $admin->id, 'is_active' => false]);

    actingAs($admin)
        ->getJson('/api/v1/admin/announcements?filter[is_active]=false')
        ->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.is_active', false);
});
