<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Notification;

use App\Models\NotificationPreference;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;
use function Pest\Laravel\putJson;

test('user can get their notification preferences', function (): void {
    $user = User::factory()->create();
    NotificationPreference::factory()->create(['user_id' => $user->id]);

    actingAs($user)
        ->getJson('/api/v1/notifications/preferences')
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.ride_updates', true)
        ->assertJsonPath('data.promo', true)
        ->assertJsonPath('data.push_enabled', true)
        ->assertJsonPath('data.email_enabled', true);
});

test('get preferences returns 404 when no preferences exist', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->getJson('/api/v1/notifications/preferences')
        ->assertNotFound();
});

test('unauthenticated user cannot get preferences', function (): void {
    getJson('/api/v1/notifications/preferences')
        ->assertUnauthorized();
});

test('user can update single preference', function (): void {
    $user = User::factory()->create();
    NotificationPreference::factory()->create(['user_id' => $user->id, 'promo' => true]);

    actingAs($user)
        ->putJson('/api/v1/notifications/preferences', [
            'promo' => false,
        ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.promo', false);

    $this->assertDatabaseHas('notification_preferences', [
        'user_id' => $user->id,
        'promo'   => 0,
    ]);
});

test('user can update multiple preferences at once', function (): void {
    $user = User::factory()->create();
    NotificationPreference::factory()->create([
        'user_id'       => $user->id,
        'ride_updates'  => true,
        'push_enabled'  => true,
        'email_enabled' => true,
    ]);

    actingAs($user)
        ->putJson('/api/v1/notifications/preferences', [
            'ride_updates'  => false,
            'push_enabled'  => false,
            'email_enabled' => false,
        ])
        ->assertOk()
        ->assertJsonPath('data.ride_updates', false)
        ->assertJsonPath('data.push_enabled', false)
        ->assertJsonPath('data.email_enabled', false);
});

test('user cannot update preferences without existing record', function (): void {
    $user = User::factory()->create();

    actingAs($user)
        ->putJson('/api/v1/notifications/preferences', [
            'promo' => false,
        ])
        ->assertNotFound();
});

test('preference values must be boolean', function (): void {
    $user = User::factory()->create();
    NotificationPreference::factory()->create(['user_id' => $user->id]);

    actingAs($user)
        ->putJson('/api/v1/notifications/preferences', [
            'promo' => 'yes',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['promo']);
});

test('unauthenticated user cannot update preferences', function (): void {
    putJson('/api/v1/notifications/preferences', [
        'promo' => false,
    ])
        ->assertUnauthorized();
});

test('user cannot see another users preferences', function (): void {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    NotificationPreference::factory()->create(['user_id' => $user1->id]);

    actingAs($user2)
        ->getJson('/api/v1/notifications/preferences')
        ->assertNotFound();
});
