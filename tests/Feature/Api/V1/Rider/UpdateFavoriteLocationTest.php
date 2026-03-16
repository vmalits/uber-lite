<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\FavoriteLocation;
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

test('rider can update favorite location', function (): void {
    $favorite = FavoriteLocation::factory()->create([
        'user_id' => $this->rider->id,
        'name'    => 'Old Name',
        'address' => 'Old Address',
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->putJson("/api/v1/rider/favorites/{$favorite->id}", [
        'name'    => 'New Name',
        'address' => 'New Address',
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.name', 'New Name')
        ->assertJsonPath('data.address', 'New Address');

    $favorite->refresh();

    expect($favorite->name)->toBe('New Name')
        ->and($favorite->address)->toBe('New Address');
});

test('rider can update location coordinates', function (): void {
    $favorite = FavoriteLocation::factory()->create([
        'user_id' => $this->rider->id,
        'lat'     => 47.010,
        'lng'     => 28.863,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->putJson("/api/v1/rider/favorites/{$favorite->id}", [
        'lat' => 47.020,
        'lng' => 28.873,
    ]);

    $response->assertOk();

    $favorite->refresh();

    expect($favorite->lat)->toBe(47.020)
        ->and($favorite->lng)->toBe(28.873);
});

test('rider cannot update another users favorite location', function (): void {
    $otherUser = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $favorite = FavoriteLocation::factory()->create([
        'user_id' => $otherUser->id,
        'name'    => 'Other User Location',
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->putJson("/api/v1/rider/favorites/{$favorite->id}", [
        'name' => 'Hacked Name',
    ]);

    $response->assertForbidden();

    $favorite->refresh();

    expect($favorite->name)->toBe('Other User Location');
});

test('driver cannot update favorite location', function (): void {
    $favorite = FavoriteLocation::factory()->create([
        'user_id' => $this->rider->id,
    ]);

    Sanctum::actingAs($this->driver);

    $response = $this->putJson("/api/v1/rider/favorites/{$favorite->id}", [
        'name' => 'Should Not Work',
    ]);

    $response->assertForbidden();
});

test('unauthenticated user cannot update favorite location', function (): void {
    $favorite = FavoriteLocation::factory()->create([
        'user_id' => $this->rider->id,
    ]);

    $response = $this->putJson("/api/v1/rider/favorites/{$favorite->id}", [
        'name' => 'Should Not Work',
    ]);

    $response->assertUnauthorized();
});

test('name must be string', function (): void {
    $favorite = FavoriteLocation::factory()->create([
        'user_id' => $this->rider->id,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->putJson("/api/v1/rider/favorites/{$favorite->id}", [
        'name' => 123,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

test('lat must be between -90 and 90', function (): void {
    $favorite = FavoriteLocation::factory()->create([
        'user_id' => $this->rider->id,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->putJson("/api/v1/rider/favorites/{$favorite->id}", [
        'lat' => 100,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['lat']);
});

test('lng must be between -180 and 180', function (): void {
    $favorite = FavoriteLocation::factory()->create([
        'user_id' => $this->rider->id,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->putJson("/api/v1/rider/favorites/{$favorite->id}", [
        'lng' => 200,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['lng']);
});

test('address must be string', function (): void {
    $favorite = FavoriteLocation::factory()->create([
        'user_id' => $this->rider->id,
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->putJson("/api/v1/rider/favorites/{$favorite->id}", [
        'address' => 123,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['address']);
});

test('empty update returns unchanged favorite location', function (): void {
    $favorite = FavoriteLocation::factory()->create([
        'user_id' => $this->rider->id,
        'name'    => 'Original Name',
        'address' => 'Original Address',
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->putJson("/api/v1/rider/favorites/{$favorite->id}", []);

    $response->assertOk()
        ->assertJsonPath('data.name', 'Original Name')
        ->assertJsonPath('data.address', 'Original Address');
});
