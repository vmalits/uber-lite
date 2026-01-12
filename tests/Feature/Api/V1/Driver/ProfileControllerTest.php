<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\User;

test('returns driver profile for authenticated driver', function () {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'email_verified_at' => now(),
    ]);
    $this->actingAs($driver);

    $response = $this->getJson('/api/v1/driver/profile');

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data' => [
            'id',
            'phone',
            'email',
            'first_name',
            'last_name',
            'avatar_urls',
            'role',
            'profile_step',
            'status',
        ],
    ]);
    $response->assertJsonPath('data.id', $driver->id);
    $response->assertJsonPath('data.role', $driver->role->value);
});

test('denies unauthenticated access', function () {
    $response = $this->getJson('/api/v1/driver/profile');
    $response->assertUnauthorized();
});
