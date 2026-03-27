<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Device;

use App\Enums\DevicePlatform;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\DeviceToken;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

function createUser(UserRole $role = UserRole::RIDER): User
{
    return User::factory()->create([
        'role'              => $role,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
}

function validPayload(): array
{
    return [
        'platform'    => DevicePlatform::IOS->value,
        'token'       => 'fKz3Y2x1aB9cD4eF5gH6iJ7kL8mN9oP0qR1sT2uV3wX4yZ5',
        'device_name' => 'iPhone 15 Pro',
        'app_version' => '1.2.0',
    ];
}

test('user can register a device token', function (): void {
    $user = createUser();

    actingAs($user)
        ->postJson('/api/v1/devices', validPayload())
        ->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.platform', 'ios')
        ->assertJsonPath('data.device_name', 'iPhone 15 Pro')
        ->assertJsonPath('data.app_version', '1.2.0');

    expect(DeviceToken::where('user_id', $user->id)->count())->toBe(1);
});

test('user can register android device', function (): void {
    $user = createUser();
    $payload = validPayload();
    $payload['platform'] = DevicePlatform::ANDROID->value;
    $payload['device_name'] = 'Samsung Galaxy S24';

    actingAs($user)
        ->postJson('/api/v1/devices', $payload)
        ->assertCreated()
        ->assertJsonPath('data.platform', 'android')
        ->assertJsonPath('data.device_name', 'Samsung Galaxy S24');
});

test('user can register web device', function (): void {
    $user = createUser();
    $payload = validPayload();
    $payload['platform'] = DevicePlatform::WEB->value;
    $payload['device_name'] = 'Chrome on macOS';

    actingAs($user)
        ->postJson('/api/v1/devices', $payload)
        ->assertCreated()
        ->assertJsonPath('data.platform', 'web');
});

test('user can register device without optional fields', function (): void {
    $user = createUser();
    $payload = [
        'platform' => DevicePlatform::IOS->value,
        'token'    => 'minimal-token-payload',
    ];

    actingAs($user)
        ->postJson('/api/v1/devices', $payload)
        ->assertCreated()
        ->assertJsonPath('data.device_name', null)
        ->assertJsonPath('data.app_version', null);
});

test('user can register multiple device tokens', function (): void {
    $user = createUser();

    actingAs($user)
        ->postJson('/api/v1/devices', validPayload())
        ->assertCreated();

    $payload2 = validPayload();
    $payload2['token'] = 'different-device-token-abc123';
    $payload2['platform'] = DevicePlatform::WEB->value;

    actingAs($user)
        ->postJson('/api/v1/devices', $payload2)
        ->assertCreated();

    expect(DeviceToken::where('user_id', $user->id)->count())->toBe(2);
});

test('platform is required', function (): void {
    $user = createUser();
    $payload = validPayload();
    unset($payload['platform']);

    actingAs($user)
        ->postJson('/api/v1/devices', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['platform']);
});

test('token is required', function (): void {
    $user = createUser();
    $payload = validPayload();
    unset($payload['token']);

    actingAs($user)
        ->postJson('/api/v1/devices', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['token']);
});

test('platform must be valid enum', function (): void {
    $user = createUser();
    $payload = validPayload();
    $payload['platform'] = 'blackberry';

    actingAs($user)
        ->postJson('/api/v1/devices', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['platform']);
});

test('token must not exceed 500 chars', function (): void {
    $user = createUser();
    $payload = validPayload();
    $payload['token'] = str_repeat('a', 501);

    actingAs($user)
        ->postJson('/api/v1/devices', $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['token']);
});

test('unauthenticated user cannot register device', function (): void {
    postJson('/api/v1/devices', validPayload())
        ->assertUnauthorized();
});

test('user can list their device tokens', function (): void {
    $user = createUser();
    DeviceToken::factory()->count(3)->create(['user_id' => $user->id]);

    actingAs($user)
        ->getJson('/api/v1/devices')
        ->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'items' => [
                    '*' => ['id', 'platform', 'device_name', 'app_version'],
                ],
                'pagination' => ['total', 'per_page', 'current_page', 'last_page'],
            ],
        ])
        ->assertJsonCount(3, 'data.items');
});

test('user cannot see other users device tokens', function (): void {
    $user1 = createUser();
    $user2 = createUser();
    DeviceToken::factory()->count(3)->create(['user_id' => $user1->id]);
    DeviceToken::factory()->count(2)->create(['user_id' => $user2->id]);

    actingAs($user2)
        ->getJson('/api/v1/devices')
        ->assertOk()
        ->assertJsonCount(2, 'data.items');
});

test('user can delete their device token', function (): void {
    $user = createUser();
    $deviceToken = DeviceToken::factory()->create(['user_id' => $user->id]);

    actingAs($user)
        ->deleteJson("/api/v1/devices/{$deviceToken->id}")
        ->assertOk()
        ->assertJsonPath('success', true);

    expect(DeviceToken::where('id', $deviceToken->id)->exists())->toBeFalse();
});

test('user cannot delete another users device token', function (): void {
    $user1 = createUser();
    $user2 = createUser();
    $deviceToken = DeviceToken::factory()->create(['user_id' => $user1->id]);

    actingAs($user2)
        ->deleteJson("/api/v1/devices/{$deviceToken->id}")
        ->assertForbidden();

    expect(DeviceToken::where('id', $deviceToken->id)->exists())->toBeTrue();
});

test('driver can register device token', function (): void {
    $driver = createUser(UserRole::DRIVER);

    actingAs($driver)
        ->postJson('/api/v1/devices', validPayload())
        ->assertCreated();
});

test('admin can register device token', function (): void {
    $admin = createUser(UserRole::ADMIN);

    actingAs($admin)
        ->postJson('/api/v1/devices', validPayload())
        ->assertCreated();
});

test('deleting user cascades to device tokens', function (): void {
    $user = createUser();
    DeviceToken::factory()->count(2)->create(['user_id' => $user->id]);

    $user->delete();

    expect(DeviceToken::where('user_id', $user->id)->exists())->toBeFalse();
});
