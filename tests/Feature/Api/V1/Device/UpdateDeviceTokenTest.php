<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Device;

use App\Enums\DevicePlatform;
use App\Models\DeviceToken;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\putJson;

test('user can update device token platform', function (): void {
    $user = createUser();
    $deviceToken = DeviceToken::factory()->create([
        'user_id'  => $user->id,
        'platform' => DevicePlatform::IOS,
    ]);

    actingAs($user)
        ->putJson("/api/v1/devices/{$deviceToken->id}", [
            'platform' => DevicePlatform::ANDROID->value,
        ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.platform', 'android');
});

test('user can update device token app version', function (): void {
    $user = createUser();
    $deviceToken = DeviceToken::factory()->create([
        'user_id'     => $user->id,
        'app_version' => '1.0.0',
    ]);

    actingAs($user)
        ->putJson("/api/v1/devices/{$deviceToken->id}", [
            'app_version' => '2.0.0',
        ])
        ->assertOk()
        ->assertJsonPath('data.app_version', '2.0.0');
});

test('user can update device token name', function (): void {
    $user = createUser();
    $deviceToken = DeviceToken::factory()->create([
        'user_id'     => $user->id,
        'device_name' => 'Old Phone',
    ]);

    actingAs($user)
        ->putJson("/api/v1/devices/{$deviceToken->id}", [
            'device_name' => 'New Phone',
        ])
        ->assertOk()
        ->assertJsonPath('data.device_name', 'New Phone');
});

test('user can update multiple fields at once', function (): void {
    $user = createUser();
    $deviceToken = DeviceToken::factory()->create([
        'user_id'     => $user->id,
        'platform'    => DevicePlatform::IOS,
        'device_name' => 'Old Phone',
        'app_version' => '1.0.0',
    ]);

    actingAs($user)
        ->putJson("/api/v1/devices/{$deviceToken->id}", [
            'platform'    => DevicePlatform::WEB->value,
            'device_name' => 'Chrome on macOS',
            'app_version' => '2.0.0',
        ])
        ->assertOk()
        ->assertJsonPath('data.platform', 'web')
        ->assertJsonPath('data.device_name', 'Chrome on macOS')
        ->assertJsonPath('data.app_version', '2.0.0');
});

test('user cannot update another users device token', function (): void {
    $user1 = createUser();
    $user2 = createUser();
    $deviceToken = DeviceToken::factory()->create(['user_id' => $user1->id]);

    actingAs($user2)
        ->putJson("/api/v1/devices/{$deviceToken->id}", [
            'app_version' => '2.0.0',
        ])
        ->assertForbidden();
});

test('platform must be valid enum', function (): void {
    $user = createUser();
    $deviceToken = DeviceToken::factory()->create(['user_id' => $user->id]);

    actingAs($user)
        ->putJson("/api/v1/devices/{$deviceToken->id}", [
            'platform' => 'blackberry',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['platform']);
});

test('unauthenticated user cannot update device token', function (): void {
    $deviceToken = DeviceToken::factory()->create();

    putJson("/api/v1/devices/{$deviceToken->id}", [
        'app_version' => '2.0.0',
    ])
        ->assertUnauthorized();
});
