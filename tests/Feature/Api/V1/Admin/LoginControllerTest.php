<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\User;

it('authenticates admin with valid credentials', function () {
    User::factory()->create([
        'phone'    => '+37360000099',
        'password' => 'admin12345',
        'role'     => UserRole::ADMIN,
    ]);

    $payload = [
        'phone'    => '+37360000099',
        'password' => 'admin12345',
    ];

    $response = $this->postJson('/api/v1/admin/login', $payload);

    $response->assertOk()
        ->assertJsonStructure([
            'message',
            'data' => ['token'],
        ])
        ->assertJson([
            'message' => 'Admin authenticated successfully',
        ]);

    $this->assertNotNull($response['data']['token'] ?? null);
});

it('rejects admin with invalid credentials', function () {
    User::factory()->create([
        'phone'    => '+37360000098',
        'password' => 'admin12345',
        'role'     => UserRole::ADMIN,
    ]);

    $payload = [
        'phone'    => '+37360000098',
        'password' => 'wrongpassword',
    ];

    $response = $this->postJson('/api/v1/admin/login', $payload);

    $response->assertUnauthorized();
});

it('rejects non-admin user', function () {
    User::factory()->create([
        'phone'    => '+37360000097',
        'password' => 'user12345',
        'role'     => UserRole::RIDER,
    ]);

    $payload = [
        'phone'    => '+37360000097',
        'password' => 'user12345',
    ];

    $response = $this->postJson('/api/v1/admin/login', $payload);

    $response->assertUnauthorized();
});

it('validates required fields', function () {
    $response = $this->postJson('/api/v1/admin/login', []);
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['phone', 'password']);
});
