<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Driver;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\DriverDocument;
use App\Models\User;

test('successfully returns list of documents for driver', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    DriverDocument::factory()->count(2)->create([
        'driver_id' => $driver->id,
    ]);

    $response = $this->actingAs($driver)->getJson('/api/v1/driver/documents');

    $response->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'type',
                    'file_path',
                    'original_name',
                    'mime_type',
                    'size',
                    'status',
                    'rejection_reason',
                    'verified_by',
                    'verified_at',
                    'expires_at',
                    'created_at' => ['human', 'string'],
                    'updated_at' => ['human', 'string'],
                ],
            ],
        ]);
});

test('returns empty list if driver has no documents', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($driver)->getJson('/api/v1/driver/documents');

    $response->assertOk()
        ->assertJsonCount(0, 'data');
});

test('does not return other drivers documents', function (): void {
    $driver1 = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
    $driver2 = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    DriverDocument::factory()->create(['driver_id' => $driver2->id]);

    $response = $this->actingAs($driver1)->getJson('/api/v1/driver/documents');

    $response->assertOk()
        ->assertJsonCount(0, 'data');
});

test('returns forbidden for rider role', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($rider)->getJson('/api/v1/driver/documents');

    $response->assertForbidden();
});

test('returns unauthorized without authentication', function (): void {
    $response = $this->getJson('/api/v1/driver/documents');

    $response->assertUnauthorized();
});
