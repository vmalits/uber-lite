<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\Redis\Factory as RedisFactory;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    Config::set('driver_location.publish.enabled', false);
});

it('driver can go online with valid coordinates', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $payload = [
        'latitude'  => 47.0105,
        'longitude' => 28.8638,
    ];

    $response = $this->postJson('/api/v1/driver/online', $payload);

    $response
        ->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'You are now online and available for rides.',
        ])
        ->assertJsonPath('data.driver_id', $user->id)
        ->assertJsonPath('data.status', 'online')
        ->assertJsonPath('data.lat', 47.0105)
        ->assertJsonPath('data.lng', 28.8638);

    $redis = app(RedisFactory::class)->connection();
    $state = $redis->get("driver:{$user->id}:state");
    $locationJson = $redis->get("driver:{$user->id}:location");
    $location = is_string($locationJson) ? json_decode($locationJson, true) : null;

    expect($state)->toBe('online')
        ->and(is_array($location))->toBeTrue()
        ->and((float) $location['lat'])->toBe(47.0105)
        ->and((float) $location['lng'])->toBe(28.8638);
});

it('updates redis location when going online again', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/driver/online', [
        'latitude'  => 47.0105,
        'longitude' => 28.8638,
    ]);

    $response = $this->postJson('/api/v1/driver/online', [
        'latitude'  => 47.1111,
        'longitude' => 28.9999,
    ]);

    $response->assertOk();

    $redis = app(RedisFactory::class)->connection();
    $locationJson = $redis->get("driver:{$user->id}:location");
    $location = is_string($locationJson) ? json_decode($locationJson, true) : null;

    expect(is_array($location))->toBeTrue()
        ->and((float) $location['lat'])->toBe(47.1111)
        ->and((float) $location['lng'])->toBe(28.9999);
});

it('denies online for driver with incomplete profile', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/online', [
        'latitude'  => 47.0105,
        'longitude' => 28.8638,
    ]);

    $response->assertForbidden();
});

it('denies online for non-driver', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/online', [
        'latitude'  => 47.0105,
        'longitude' => 28.8638,
    ]);

    $response->assertForbidden();
});

it('denies online for unauthenticated user', function (): void {
    $response = $this->postJson('/api/v1/driver/online', [
        'latitude'  => 47.0105,
        'longitude' => 28.8638,
    ]);

    $response->assertUnauthorized();
});

it('validates required fields', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/online', []);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['latitude', 'longitude']);
});

it('validates latitude range', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/online', [
        'latitude'  => 100,
        'longitude' => 28.8638,
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['latitude']);
});

it('validates longitude range', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/online', [
        'latitude'  => 47.0105,
        'longitude' => 200,
    ]);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['longitude']);
});
