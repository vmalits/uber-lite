<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Driver;

use App\Enums\DriverDocumentStatus;
use App\Enums\DriverDocumentType;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Http\UploadedFile;

test('successfully uploads a document for driver', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $file = UploadedFile::fake()->create('license.pdf', 1000, 'application/pdf');

    $response = $this->actingAs($driver)->postJson('/api/v1/driver/documents', [
        'type'     => DriverDocumentType::DRIVING_LICENSE->value,
        'document' => $file,
    ]);

    $response->assertCreated()
        ->assertJsonFragment([
            'message' => 'Document uploaded successfully.',
        ])
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
        ]);

    $this->assertDatabaseHas('driver_documents', [
        'driver_id'     => $driver->id,
        'type'          => DriverDocumentType::DRIVING_LICENSE->value,
        'original_name' => 'license.pdf',
        'status'        => DriverDocumentStatus::PENDING->value,
    ]);
});

test('validates required fields', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($driver)->postJson('/api/v1/driver/documents', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['type', 'document']);
});

test('validates document type enum', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $file = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');

    $response = $this->actingAs($driver)->postJson('/api/v1/driver/documents', [
        'type'     => 'invalid_type',
        'document' => $file,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['type']);
});

test('validates file mimes', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $file = UploadedFile::fake()->create('doc.exe', 100, 'application/octet-stream');

    $response = $this->actingAs($driver)->postJson('/api/v1/driver/documents', [
        'type'     => DriverDocumentType::DRIVING_LICENSE->value,
        'document' => $file,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['document']);
});

test('returns unauthorized without authentication', function (): void {
    $response = $this->postJson('/api/v1/driver/documents', []);

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

    $file = UploadedFile::fake()->create('license.pdf', 100, 'application/pdf');

    $response = $this->actingAs($rider)->postJson('/api/v1/driver/documents', [
        'type'     => DriverDocumentType::DRIVING_LICENSE->value,
        'document' => $file,
    ]);

    $response->assertForbidden();
});
