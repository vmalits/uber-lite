<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Contracts\Redis\Factory as RedisFactory;
use Laravel\Sanctum\Sanctum;

it('driver can go offline', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/driver/online', [
        'latitude'  => 47.0105,
        'longitude' => 28.8638,
    ]);

    $response = $this->postJson('/api/v1/driver/offline');

    $response
        ->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'You are now offline and will not receive ride requests.',
        ])
        ->assertJsonPath('data.driver_id', $user->id)
        ->assertJsonPath('data.status', 'offline');

    $redis = app(RedisFactory::class)->connection();
    $state = $redis->get("driver:{$user->id}:state");
    $location = $redis->get("driver:{$user->id}:location");
    $geoScore = $redis->zscore('drivers:geo', $user->id);
    $isOnline = $redis->sismember('drivers:online', $user->id);

    $hasGeo = $geoScore !== false && $geoScore !== null;

    expect($state)->toBeNull()
        ->and($location)->toBeNull()
        ->and($hasGeo)->toBeFalse()
        ->and((int) $isOnline)->toBe(0);
});

it('denies offline for driver with incomplete profile', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/offline');

    $response->assertForbidden();
});

it('denies offline for non-driver', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/offline');

    $response->assertForbidden();
});

it('denies offline for unauthenticated user', function (): void {
    $response = $this->postJson('/api/v1/driver/offline');

    $response->assertUnauthorized();
});
