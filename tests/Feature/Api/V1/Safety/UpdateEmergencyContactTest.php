<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\EmergencyContact;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $this->driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
});

test('rider can update emergency contact', function (): void {
    $contact = EmergencyContact::factory()->create([
        'user_id' => $this->rider->id,
        'name'    => 'Old Name',
        'phone'   => '+1111111111',
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->putJson("/api/v1/safety/contacts/{$contact->id}", [
        'name'  => 'New Name',
        'phone' => '+2222222222',
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.name', 'New Name')
        ->assertJsonPath('data.phone', '+2222222222');

    $contact->refresh();

    expect($contact->name)->toBe('New Name')
        ->and($contact->phone)->toBe('+2222222222');
});

test('driver can update emergency contact', function (): void {
    $contact = EmergencyContact::factory()->create([
        'user_id' => $this->driver->id,
        'name'    => 'Original Name',
    ]);

    Sanctum::actingAs($this->driver);

    $response = $this->putJson("/api/v1/safety/contacts/{$contact->id}", [
        'name' => 'Updated Name',
    ]);

    $response->assertOk()
        ->assertJsonPath('data.name', 'Updated Name');
});

test('rider can set contact as primary', function (): void {
    $contact = EmergencyContact::factory()->create([
        'user_id'    => $this->rider->id,
        'is_primary' => false,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->putJson("/api/v1/safety/contacts/{$contact->id}", [
        'is_primary' => true,
    ]);

    $response->assertOk();

    $contact->refresh();

    expect($contact->is_primary)->toBeTrue();
});

test('rider can update email', function (): void {
    $contact = EmergencyContact::factory()->create([
        'user_id' => $this->rider->id,
        'email'   => 'old@example.com',
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->putJson("/api/v1/safety/contacts/{$contact->id}", [
        'email' => 'new@example.com',
    ]);

    $response->assertOk()
        ->assertJsonPath('data.email', 'new@example.com');
});

test('rider cannot update another users contact', function (): void {
    $otherUser = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $contact = EmergencyContact::factory()->create([
        'user_id' => $otherUser->id,
        'name'    => 'Other User Contact',
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->putJson("/api/v1/safety/contacts/{$contact->id}", [
        'name' => 'Hacked Name',
    ]);

    $response->assertForbidden();

    $contact->refresh();

    expect($contact->name)->toBe('Other User Contact');
});

test('unauthenticated user cannot update contact', function (): void {
    $contact = EmergencyContact::factory()->create([
        'user_id' => $this->rider->id,
    ]);

    $response = $this->putJson("/api/v1/safety/contacts/{$contact->id}", [
        'name' => 'Should Not Work',
    ]);

    $response->assertUnauthorized();
});

test('name must be string', function (): void {
    $contact = EmergencyContact::factory()->create([
        'user_id' => $this->rider->id,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->putJson("/api/v1/safety/contacts/{$contact->id}", [
        'name' => 123,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

test('phone must be string', function (): void {
    $contact = EmergencyContact::factory()->create([
        'user_id' => $this->rider->id,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->putJson("/api/v1/safety/contacts/{$contact->id}", [
        'phone' => 1234567890,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['phone']);
});

test('email must be valid', function (): void {
    $contact = EmergencyContact::factory()->create([
        'user_id' => $this->rider->id,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->putJson("/api/v1/safety/contacts/{$contact->id}", [
        'email' => 'invalid-email',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

test('empty update returns unchanged contact', function (): void {
    $contact = EmergencyContact::factory()->create([
        'user_id' => $this->rider->id,
        'name'    => 'Original Name',
        'phone'   => '+1234567890',
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->putJson("/api/v1/safety/contacts/{$contact->id}", []);

    $response->assertOk()
        ->assertJsonPath('data.name', 'Original Name')
        ->assertJsonPath('data.phone', '+1234567890');
});
