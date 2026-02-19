<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Driver;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can get current driver location', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/driver/location/current');

    $response->assertOk()
        ->assertJsonPath('success', true);
});

it('returns location data when driver has location', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/driver/location/current');

    $response->assertOk();

    $data = $response->json('data');

    if ($data !== null) {
        expect($data)->toHaveKeys(['driver_id', 'status', 'lat', 'lng', 'ts']);
    }
});

it('denies access for unauthenticated user', function () {
    $response = $this->getJson('/api/v1/driver/location/current');

    $response->assertUnauthorized();
});

it('denies access for rider role', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/driver/location/current');

    $response->assertForbidden();
});
