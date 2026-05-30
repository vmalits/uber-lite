<?php

declare(strict_types=1);

use App\Enums\DriverDocumentStatus;
use App\Enums\UserRole;
use App\Models\DriverDocument;
use App\Models\User;

it('approves a document successfully', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $driver = User::factory()->create();

    $document = DriverDocument::factory()->create([
        'driver_id' => $driver->id,
        'status'    => DriverDocumentStatus::PENDING,
    ]);

    $response = $this->actingAs($admin)->putJson("/api/v1/admin/documents/{$document->id}/verify", [
        'status' => DriverDocumentStatus::APPROVED->value,
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'type',
                'status',
                'verified_at',
                'verified_by',
            ],
        ])
        ->assertJsonPath('data.id', $document->id);

    $this->assertDatabaseHas('driver_documents', [
        'id'          => $document->id,
        'status'      => DriverDocumentStatus::APPROVED->value,
        'verified_by' => $admin->id,
    ]);

    expect($document->fresh()->verified_at)->not->toBeNull();
});

it('rejects a document with reason', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $driver = User::factory()->create();

    $document = DriverDocument::factory()->create([
        'driver_id' => $driver->id,
        'status'    => DriverDocumentStatus::PENDING,
    ]);

    $response = $this->actingAs($admin)->putJson("/api/v1/admin/documents/{$document->id}/verify", [
        'status'           => DriverDocumentStatus::REJECTED->value,
        'rejection_reason' => 'Document is expired',
    ]);

    $response->assertOk();

    $this->assertDatabaseHas('driver_documents', [
        'id'               => $document->id,
        'status'           => DriverDocumentStatus::REJECTED->value,
        'rejection_reason' => 'Document is expired',
        'verified_by'      => $admin->id,
    ]);
});

it('validates required status field', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $document = DriverDocument::factory()->create([
        'status' => DriverDocumentStatus::PENDING,
    ]);

    $response = $this->actingAs($admin)->putJson("/api/v1/admin/documents/{$document->id}/verify", []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['status']);
});

it('validates rejection reason is required when rejecting', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $document = DriverDocument::factory()->create([
        'status' => DriverDocumentStatus::PENDING,
    ]);

    $response = $this->actingAs($admin)->putJson("/api/v1/admin/documents/{$document->id}/verify", [
        'status' => DriverDocumentStatus::REJECTED->value,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['rejection_reason']);
});

it('validates status must be approved or rejected', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $document = DriverDocument::factory()->create([
        'status' => DriverDocumentStatus::PENDING,
    ]);

    $response = $this->actingAs($admin)->putJson("/api/v1/admin/documents/{$document->id}/verify", [
        'status' => DriverDocumentStatus::PENDING->value,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['status']);
});

it('returns forbidden for non-admin user', function () {
    $driver = User::factory()->create();

    $document = DriverDocument::factory()->create([
        'status' => DriverDocumentStatus::PENDING,
    ]);

    $response = $this->actingAs($driver)->putJson("/api/v1/admin/documents/{$document->id}/verify", [
        'status' => DriverDocumentStatus::APPROVED->value,
    ]);

    $response->assertForbidden();
});

it('returns unauthorized without authentication', function () {
    $document = DriverDocument::factory()->create([
        'status' => DriverDocumentStatus::PENDING,
    ]);

    $response = $this->putJson("/api/v1/admin/documents/{$document->id}/verify", [
        'status' => DriverDocumentStatus::APPROVED->value,
    ]);

    $response->assertUnauthorized();
});
