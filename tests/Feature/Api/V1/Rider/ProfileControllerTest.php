<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\User;

test('returns rider profile for authenticated rider', function () {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'email_verified_at' => now(),
    ]);
    $this->actingAs($rider);

    $response = $this->getJson('/api/v1/rider/profile');

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
    $response->assertJsonPath('data.id', $rider->id);
    $response->assertJsonPath('data.role', $rider->role->value);
});

test('denies unauthenticated access', function () {
    $response = $this->getJson('/api/v1/rider/profile');
    $response->assertUnauthorized();
});
