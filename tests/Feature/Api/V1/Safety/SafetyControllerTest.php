<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\EmergencyContact;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
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

test('rider can add emergency contact', function (): void {
    Sanctum::actingAs($this->rider);

    $response = $this->postJson('/api/v1/safety/contacts', [
        'name'       => 'John Doe',
        'phone'      => '+1234567890',
        'email'      => 'john@example.com',
        'is_primary' => true,
    ]);

    $response->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.name', 'John Doe')
        ->assertJsonPath('data.phone', '+1234567890')
        ->assertJsonPath('data.email', 'john@example.com');

    expect($this->rider->emergencyContacts()->count())->toBe(1);
});

test('driver can add emergency contact', function (): void {
    Sanctum::actingAs($this->driver);

    $response = $this->postJson('/api/v1/safety/contacts', [
        'name'  => 'Jane Doe',
        'phone' => '+0987654321',
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.name', 'Jane Doe');
});

test('rider can get emergency contacts', function (): void {
    EmergencyContact::factory()->create([
        'user_id' => $this->rider->id,
        'name'    => 'Contact 1',
    ]);
    EmergencyContact::factory()->create([
        'user_id' => $this->rider->id,
        'name'    => 'Contact 2',
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson('/api/v1/safety/contacts');

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

test('rider can delete emergency contact', function (): void {
    $contact = EmergencyContact::factory()->create([
        'user_id' => $this->rider->id,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->deleteJson("/api/v1/safety/contacts/{$contact->id}");

    $response->assertOk()
        ->assertJsonPath('success', true);

    expect(EmergencyContact::find($contact->id))->toBeNull();
});

test('rider cannot delete another users contact', function (): void {
    $otherUser = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $contact = EmergencyContact::factory()->create([
        'user_id' => $otherUser->id,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->deleteJson("/api/v1/safety/contacts/{$contact->id}");

    $response->assertForbidden();
});

test('rider can send SOS alert', function (): void {
    Notification::fake();

    $contact = EmergencyContact::factory()->create([
        'user_id' => $this->rider->id,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson('/api/v1/safety/sos', [
        'latitude'  => 47.0105,
        'longitude' => 28.8638,
        'message'   => 'I need help!',
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', __('messages.safety.sos_sent'));
});

test('rider cannot send SOS without contacts', function (): void {
    Sanctum::actingAs($this->rider);

    $response = $this->postJson('/api/v1/safety/sos', [
        'latitude'  => 47.0105,
        'longitude' => 28.8638,
    ]);

    $response->assertStatus(400)
        ->assertJsonPath('success', false)
        ->assertJsonPath('message', __('messages.safety.no_emergency_contacts'));
});

test('driver can send SOS alert', function (): void {
    Notification::fake();

    EmergencyContact::factory()->create([
        'user_id' => $this->driver->id,
    ]);

    Sanctum::actingAs($this->driver);

    $response = $this->postJson('/api/v1/safety/sos', [
        'latitude'  => 47.0105,
        'longitude' => 28.8638,
    ]);

    $response->assertOk();
});

test('unverified user cannot access safety endpoints', function (): void {
    $unverifiedUser = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($unverifiedUser);

    $response = $this->getJson('/api/v1/safety/contacts');

    $response->assertForbidden();
});

test('unauthorized user cannot access safety endpoints', function (): void {
    $response = $this->getJson('/api/v1/safety/contacts');

    $response->assertUnauthorized();
});

test('SOS validation requires latitude and longitude', function (): void {
    EmergencyContact::factory()->create([
        'user_id' => $this->rider->id,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->postJson('/api/v1/safety/sos', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['latitude', 'longitude']);
});
