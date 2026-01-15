<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('returns ride estimate successfully', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $payload = [
        'origin_address'      => 'bd. Ștefan cel Mare și Sfânt, 1, Chișinău',
        'origin_lat'          => 47.0105,
        'origin_lng'          => 28.8638,
        'destination_address' => 'str. Mihai Eminescu, 50, Chișinău',
        'destination_lat'     => 47.0225,
        'destination_lng'     => 28.8353,
    ];

    $response = $this->postJson('/api/v1/rider/estimates', $payload);

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'distance_km',
                'duration_minutes',
                'price',
            ],
            'message',
        ])
        ->assertJsonPath('success', true);

    expect($response->json('data.distance_km'))->toBeGreaterThan(0)
        ->and($response->json('data.duration_minutes'))->toBeGreaterThan(0)
        ->and($response->json('data.price'))->toBeGreaterThan(0);
});

it('denies estimate for rider with incomplete profile', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $payload = [
        'origin_address'      => 'bd. Ștefan cel Mare și Sfânt, 1, Chișinău',
        'origin_lat'          => 47.0105,
        'origin_lng'          => 28.8638,
        'destination_address' => 'str. Mihai Eminescu, 50, Chișinău',
        'destination_lat'     => 47.0225,
        'destination_lng'     => 28.8353,
    ];

    $response = $this->postJson('/api/v1/rider/estimates', $payload);

    $response->assertForbidden();
});

it('denies estimate for non-rider', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $payload = [
        'origin_address'      => 'bd. Ștefan cel Mare și Sfânt, 1, Chișinău',
        'origin_lat'          => 47.0105,
        'origin_lng'          => 28.8638,
        'destination_address' => 'str. Mihai Eminescu, 50, Chișinău',
        'destination_lat'     => 47.0225,
        'destination_lng'     => 28.8353,
    ];

    $response = $this->postJson('/api/v1/rider/estimates', $payload);

    $response->assertForbidden();
});

it('denies estimate for unauthenticated user', function () {
    $payload = [
        'origin_address'      => 'bd. Ștefan cel Mare și Sfânt, 1, Chișinău',
        'origin_lat'          => 47.0105,
        'origin_lng'          => 28.8638,
        'destination_address' => 'str. Mihai Eminescu, 50, Chișinău',
        'destination_lat'     => 47.0225,
        'destination_lng'     => 28.8353,
    ];

    $response = $this->postJson('/api/v1/rider/estimates', $payload);

    $response->assertUnauthorized();
});

it('validates required fields', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/rider/estimates', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'origin_address',
            'origin_lat',
            'origin_lng',
            'destination_address',
            'destination_lat',
            'destination_lng',
        ]);
});

it('validates coordinates range', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/rider/estimates', [
        'origin_address'      => 'Test Address',
        'origin_lat'          => 100,
        'origin_lng'          => 200,
        'destination_address' => 'Test Address 2',
        'destination_lat'     => 100,
        'destination_lng'     => 200,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'origin_lat',
            'origin_lng',
            'destination_lat',
            'destination_lng',
        ]);
});

it('prevents estimate when active ride exists', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::PENDING->value,
    ]);

    $payload = [
        'origin_address'      => 'bd. Ștefan cel Mare și Sfânt, 1, Chișinău',
        'origin_lat'          => 47.0105,
        'origin_lng'          => 28.8638,
        'destination_address' => 'str. Mihai Eminescu, 50, Chișinău',
        'destination_lat'     => 47.0225,
        'destination_lng'     => 28.8353,
    ];

    $response = $this->postJson('/api/v1/rider/estimates', $payload);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['ride']);
});
