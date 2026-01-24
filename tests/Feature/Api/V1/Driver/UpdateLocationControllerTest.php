<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\User;
use denis660\Centrifugo\Centrifugo;
use Illuminate\Contracts\Redis\Factory as RedisFactory;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    Config::set('driver_location.publish.enabled', false);
});

it('driver with completed profile can update location', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $payload = [
        'lat' => 55.7558,
        'lng' => 37.6173,
    ];

    $response = $this->postJson('/api/v1/driver/location', $payload);

    $response
        ->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Driver location updated successfully.',
        ])
        ->assertJsonPath('data.driver_id', $user->id)
        ->assertJsonPath('data.lat', 55.7558)
        ->assertJsonPath('data.lng', 37.6173);

    $stateKey = "driver:{$user->id}:state";
    $locationKey = "driver:{$user->id}:location";

    $redis = app(RedisFactory::class)->connection();
    $state = $redis->get($stateKey);
    $redisLocation = $redis->get($locationKey);
    $decodedLocation = is_string($redisLocation) ? json_decode(
        $redisLocation, true, 512, JSON_THROW_ON_ERROR,
    ) : null;

    expect($state)->toBe('online')
        ->and(is_array($decodedLocation))->toBeTrue()
        ->and((float) $decodedLocation['lat'])->toBe(55.7558)
        ->and((float) $decodedLocation['lng'])->toBe(37.6173)
        ->and((int) $decodedLocation['ts'])->toBeGreaterThan(0);
});

it('denies update for driver with incomplete profile', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/location', [
        'lat' => 10.0,
        'lng' => 20.0,
    ]);

    $response->assertForbidden();
});

it('denies update for non-driver', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/location', [
        'lat' => 10.0,
        'lng' => 20.0,
    ]);

    $response->assertForbidden();
});

it('denies update for unauthenticated user', function (): void {
    $response = $this->postJson('/api/v1/driver/location', [
        'lat' => 10.0,
        'lng' => 20.0,
    ]);

    $response->assertUnauthorized();
});

it('validates required fields', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/location', []);

    $response
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['lat', 'lng']);
});

it('publishes location to region channel when publish flag is true', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Config::set('driver_location.publish.enabled', true);
    Config::set('driver_location.publish.admin_region', true);
    Config::set('driver_location.publish.min_interval_seconds', 0);
    Config::set('driver_location.publish.min_distance_meters', 0);
    Config::set('driver_location.publish.region_precision', 2);

    $centrifugo = Mockery::mock(Centrifugo::class);
    $centrifugo->shouldReceive('publish')
        ->once()
        ->withArgs(function (string $channel, array $payload, bool $skipHistory) use ($driver) {
            expect($channel)->toBe('region:55.76:37.62')
                ->and($skipHistory)->toBeTrue()
                ->and($payload['event'])->toBe('driver.location_updated')
                ->and($payload['data']['driver_id'])->toBe($driver->id)
                ->and($payload['data']['lat'])->toBe(55.7558)
                ->and($payload['data']['lng'])->toBe(37.6173);

            return true;
        })
        ->andReturn([]);

    app()->instance(Centrifugo::class, $centrifugo);

    Sanctum::actingAs($driver);

    $response = $this->postJson('/api/v1/driver/location', [
        'lat' => 55.7558,
        'lng' => 37.6173,
    ]);

    $response->assertOk();
});
