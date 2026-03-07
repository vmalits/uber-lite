<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

it('returns current surge multiplier for rider', function () {
    $rider = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
    $rider->markEmailAsVerified();

    Cache::flush();

    $response = $this->actingAs($rider, 'sanctum')
        ->getJson('/api/v1/rider/pricing/surge');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'multiplier',
                'reason',
                'is_active',
            ],
        ]);
});

it('returns default surge when no demand', function () {
    $rider = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
    $rider->markEmailAsVerified();

    Cache::flush();

    $response = $this->actingAs($rider, 'sanctum')
        ->getJson('/api/v1/rider/pricing/surge');

    $response->assertOk();

    expect($response['data']['multiplier'])->toBeFloat();
});

it('rejects unauthorized request without token', function () {
    $response = $this->getJson('/api/v1/rider/pricing/surge');
    $response->assertUnauthorized();
});

it('rejects non-rider user', function () {
    $driver = User::factory()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
    $driver->markEmailAsVerified();

    $response = $this->actingAs($driver, 'sanctum')
        ->getJson('/api/v1/rider/pricing/surge');
    $response->assertForbidden();
});
