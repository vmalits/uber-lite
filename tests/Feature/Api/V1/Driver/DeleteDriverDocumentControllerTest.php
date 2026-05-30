<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Driver;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\DriverDocument;
use App\Models\User;

test('successfully deletes a document for driver', function (): void {
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

    $response = $this->actingAs($driver)->deleteJson("/api/v1/driver/documents/{$document->id}");

    $response->assertOk()
        ->assertJsonFragment([
            'message' => 'Deleted successfully',
        ]);

    $this->assertDatabaseMissing('driver_documents', [
        'id' => $document->id,
    ]);
});

test('returns forbidden when deleting someone else\'s document', function (): void {
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

    $response = $this->actingAs($driver1)->deleteJson("/api/v1/driver/documents/{$document->id}");

    $response->assertForbidden();

    $this->assertDatabaseHas('driver_documents', [
        'id' => $document->id,
    ]);
});

test('returns forbidden for rider role on DELETE', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $document = DriverDocument::factory()->create();

    $response = $this->actingAs($rider)->deleteJson("/api/v1/driver/documents/{$document->id}");

    $response->assertForbidden();

    $this->assertDatabaseHas('driver_documents', [
        'id' => $document->id,
    ]);
});

test('returns unauthorized for unauthenticated user on DELETE', function (): void {
    $document = DriverDocument::factory()->create();

    $response = $this->deleteJson("/api/v1/driver/documents/{$document->id}");

    $response->assertUnauthorized();

    $this->assertDatabaseHas('driver_documents', [
        'id' => $document->id,
    ]);
});
