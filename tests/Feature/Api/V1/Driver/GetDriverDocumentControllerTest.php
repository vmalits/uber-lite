<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Driver;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\DriverDocument;
use App\Models\User;

test('successfully returns a single document for driver', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $document = DriverDocument::factory()->create([
        'driver_id' => $driver->id,
    ]);

    $response = $this->actingAs($driver)->getJson("/api/v1/driver/documents/{$document->id}");

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
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
        ])
        ->assertJsonPath('data.id', $document->id);
});

test('returns forbidden when viewing someone else\'s document', function (): void {
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

    $document = DriverDocument::factory()->create([
        'driver_id' => $driver2->id,
    ]);

    $response = $this->actingAs($driver1)->getJson("/api/v1/driver/documents/{$document->id}");

    $response->assertForbidden();
});

test('returns unauthorized without authentication', function (): void {
    $document = DriverDocument::factory()->create();

    $response = $this->getJson("/api/v1/driver/documents/{$document->id}");

    $response->assertUnauthorized();
});

test('returns forbidden for rider role', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $document = DriverDocument::factory()->create();

    $response = $this->actingAs($rider)->getJson("/api/v1/driver/documents/{$document->id}");

    $response->assertForbidden();
});
