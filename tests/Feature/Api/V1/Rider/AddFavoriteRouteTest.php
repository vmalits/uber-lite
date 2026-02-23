<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

test('rider can add favorite route', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->postJson('/api/v1/rider/routes/favorite', [
            'name'                => 'Home to Work',
            'origin_address'      => '123 Main St, New York',
            'origin_lat'          => 40.7128,
            'origin_lng'          => -74.0060,
            'destination_address' => '456 Park Ave, New York',
            'destination_lat'     => 40.7589,
            'destination_lng'     => -73.9851,
            'type'                => 'work',
        ])
        ->assertStatus(201)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.name', 'Home to Work')
        ->assertJsonPath('data.type', 'work');

    $this->assertDatabaseHas('favorite_routes', [
        'user_id'             => $rider->id,
        'name'                => 'Home to Work',
        'origin_address'      => '123 Main St, New York',
        'destination_address' => '456 Park Ave, New York',
        'type'                => 'work',
    ]);
});

test('rider can add favorite route without type', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->postJson('/api/v1/rider/routes/favorite', [
            'name'                => 'Gym Route',
            'origin_address'      => 'Home Address',
            'origin_lat'          => 40.7128,
            'origin_lng'          => -74.0060,
            'destination_address' => 'Gym Address',
            'destination_lat'     => 40.7589,
            'destination_lng'     => -73.9851,
        ])
        ->assertStatus(201)
        ->assertJsonPath('success', true);
});

test('route type must be valid', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->postJson('/api/v1/rider/routes/favorite', [
            'name'                => 'Test Route',
            'origin_address'      => 'Origin',
            'origin_lat'          => 40.7128,
            'origin_lng'          => -74.0060,
            'destination_address' => 'Destination',
            'destination_lat'     => 40.7589,
            'destination_lng'     => -73.9851,
            'type'                => 'invalid_type',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['type']);
});

test('coordinates must be valid', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->postJson('/api/v1/rider/routes/favorite', [
            'name'                => 'Test Route',
            'origin_address'      => 'Origin',
            'origin_lat'          => 999,
            'origin_lng'          => -74.0060,
            'destination_address' => 'Destination',
            'destination_lat'     => 40.7589,
            'destination_lng'     => -73.9851,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['origin_lat']);
});

test('required fields must be present', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->postJson('/api/v1/rider/routes/favorite', [])
        ->assertStatus(422)
        ->assertJsonValidationErrors([
            'name',
            'origin_address',
            'origin_lat',
            'origin_lng',
            'destination_address',
            'destination_lat',
            'destination_lng',
        ]);
});

test('unauthenticated user cannot add favorite route', function (): void {
    postJson('/api/v1/rider/routes/favorite', [
        'name'                => 'Test Route',
        'origin_address'      => 'Origin',
        'origin_lat'          => 40.7128,
        'origin_lng'          => -74.0060,
        'destination_address' => 'Destination',
        'destination_lat'     => 40.7589,
        'destination_lng'     => -73.9851,
    ])
        ->assertStatus(401);
});

test('driver cannot add favorite route', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($driver)
        ->postJson('/api/v1/rider/routes/favorite', [
            'name'                => 'Test Route',
            'origin_address'      => 'Origin',
            'origin_lat'          => 40.7128,
            'origin_lng'          => -74.0060,
            'destination_address' => 'Destination',
            'destination_lat'     => 40.7589,
            'destination_lng'     => -73.9851,
        ])
        ->assertStatus(403);
});

test('rider can add multiple favorite routes', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->postJson('/api/v1/rider/routes/favorite', [
            'name'                => 'Home to Work',
            'origin_address'      => 'Home',
            'origin_lat'          => 40.7128,
            'origin_lng'          => -74.0060,
            'destination_address' => 'Work',
            'destination_lat'     => 40.7589,
            'destination_lng'     => -73.9851,
            'type'                => 'work',
        ])
        ->assertStatus(201);

    actingAs($rider)
        ->postJson('/api/v1/rider/routes/favorite', [
            'name'                => 'Home to Gym',
            'origin_address'      => 'Home',
            'origin_lat'          => 40.7128,
            'origin_lng'          => -74.0060,
            'destination_address' => 'Gym',
            'destination_lat'     => 40.7500,
            'destination_lng'     => -73.9900,
            'type'                => 'gym',
        ])
        ->assertStatus(201);

    $this->assertEquals(2, App\Models\FavoriteRoute::where('user_id', $rider->id)->count());
});
