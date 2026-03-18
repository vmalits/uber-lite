<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->verified()->create([
        'role' => UserRole::RIDER,
    ]);
});

function createNotification(User $user, array $data = []): string
{
    $id = (string) Illuminate\Support\Str::ulid();

    DB::table('notifications')->insert(array_merge([
        'id'         => $id,
        'user_id'    => $user->id,
        'type'       => 'App\\Notifications\\TestNotification',
        'title'      => 'Test Title',
        'body'       => 'Test body text',
        'data'       => json_encode(['key' => 'value', 'title' => 'Test Title', 'body' => 'Test body text']),
        'read_at'    => null,
        'created_at' => now(),
        'updated_at' => now(),
    ], $data));

    return $id;
}

it('can list all notifications', function () {
    createNotification($this->user);
    createNotification($this->user, [
        'id'    => (string) Illuminate\Support\Str::ulid(),
        'title' => 'Second Notification',
        'body'  => 'Second body',
        'data'  => json_encode(['key' => 'value2', 'title' => 'Second Notification', 'body' => 'Second body']),
    ]);

    Sanctum::actingAs($this->user);

    $response = $this->getJson('/api/v1/notifications');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonCount(2, 'data.items');
});

it('returns only unread notifications on unread endpoint', function () {
    createNotification($this->user, [
        'read_at' => now(),
    ]);
    createNotification($this->user);

    Sanctum::actingAs($this->user);

    $response = $this->getJson('/api/v1/notifications/unread');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonCount(1, 'data.items');
});

it('returns unread count', function () {
    createNotification($this->user);
    createNotification($this->user);
    createNotification($this->user, [
        'read_at' => now(),
    ]);

    Sanctum::actingAs($this->user);

    $response = $this->getJson('/api/v1/notifications/unread/count');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.count', 2);
});

it('returns zero unread count when all are read', function () {
    createNotification($this->user, [
        'read_at' => now(),
    ]);

    Sanctum::actingAs($this->user);

    $response = $this->getJson('/api/v1/notifications/unread/count');

    $response->assertOk()
        ->assertJsonPath('data.count', 0);
});

it('can mark a single notification as read', function () {
    $id = createNotification($this->user);

    Sanctum::actingAs($this->user);

    $response = $this->putJson("/api/v1/notifications/{$id}/read");

    $response->assertOk()
        ->assertJsonPath('success', true);

    expect(DB::table('notifications')->where('id', $id)->value('read_at'))->not->toBeNull();
});

it('can mark all notifications as read', function () {
    createNotification($this->user);
    createNotification($this->user);
    createNotification($this->user);

    Sanctum::actingAs($this->user);

    $response = $this->postJson('/api/v1/notifications/read-all');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.marked_count', 3);

    expect(DB::table('notifications')->where('user_id', $this->user->id)->whereNull('read_at')->count())->toBe(0);
});

it('can delete a notification', function () {
    $id = createNotification($this->user);

    Sanctum::actingAs($this->user);

    $response = $this->deleteJson("/api/v1/notifications/{$id}");

    $response->assertOk()
        ->assertJsonPath('success', true);

    expect(DB::table('notifications')->where('id', $id)->exists())->toBeFalse();
});

it('cannot see another users notifications', function () {
    $otherUser = User::factory()->verified()->create([
        'role' => UserRole::RIDER,
    ]);
    createNotification($otherUser);

    Sanctum::actingAs($this->user);

    $response = $this->getJson('/api/v1/notifications');

    $response->assertOk()
        ->assertJsonCount(0, 'data.items');
});

it('cannot mark another users notification as read', function () {
    $otherUser = User::factory()->verified()->create([
        'role' => UserRole::RIDER,
    ]);
    $id = createNotification($otherUser);

    Sanctum::actingAs($this->user);

    $this->putJson("/api/v1/notifications/{$id}/read")->assertOk();

    expect(DB::table('notifications')->where('id', $id)->value('read_at'))->toBeNull();
});

it('cannot delete another users notification', function () {
    $otherUser = User::factory()->verified()->create([
        'role' => UserRole::RIDER,
    ]);
    $id = createNotification($otherUser);

    Sanctum::actingAs($this->user);

    $this->deleteJson("/api/v1/notifications/{$id}")->assertOk();

    expect(DB::table('notifications')->where('id', $id)->exists())->toBeTrue();
});

it('denies access for unauthenticated user', function () {
    $response = $this->getJson('/api/v1/notifications');

    $response->assertUnauthorized();
});

it('can filter notifications by type', function () {
    createNotification($this->user, [
        'type' => 'App\\Notifications\\Gamification\\AchievementUnlockedNotification',
        'data' => json_encode(['title' => 'Achievement']),
    ]);
    createNotification($this->user, [
        'id'    => (string) Illuminate\Support\Str::ulid(),
        'type'  => 'App\\Notifications\\Streak\\StreakLevelUpNotification',
        'title' => 'Streak',
        'data'  => json_encode(['title' => 'Streak']),
    ]);

    Sanctum::actingAs($this->user);

    $response = $this->getJson('/api/v1/notifications?filter[type]=Achievement');

    $response->assertOk()
        ->assertJsonCount(1, 'data.items');
});
