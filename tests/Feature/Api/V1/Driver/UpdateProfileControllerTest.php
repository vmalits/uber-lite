<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;

beforeEach(function (): void {
    $this->user = User::factory()->create([
        'first_name'        => 'John',
        'last_name'         => 'Doe',
        'role'              => UserRole::DRIVER->value,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
});

it('successfully updates driver profile', function (): void {
    $response = $this->actingAs($this->user)->putJson('/api/v1/driver/profile', [
        'first_name' => 'Jane',
        'last_name'  => 'Smith',
    ]);

    $response->assertOk()
        ->assertJsonStructure([
            'message',
            'data' => [
                'id',
                'phone',
                'first_name',
                'last_name',
                'email',
                'role',
                'profile_step',
                'avatar_urls',
            ],
        ])
        ->assertJson([
            'message' => 'Profile updated successfully.',
            'data'    => [
                'first_name' => 'Jane',
                'last_name'  => 'Smith',
            ],
        ]);

    $this->user->refresh();

    expect($this->user->first_name)->toBe('Jane')
        ->and($this->user->last_name)->toBe('Smith');
});

it('updates only first_name', function (): void {
    $response = $this->actingAs($this->user)->putJson('/api/v1/driver/profile', [
        'first_name' => 'Jane',
    ]);

    $response->assertOk()
        ->assertJson([
            'message' => 'Profile updated successfully.',
            'data'    => [
                'first_name' => 'Jane',
                'last_name'  => 'Doe',
            ],
        ]);

    $this->user->refresh();

    expect($this->user->first_name)->toBe('Jane')
        ->and($this->user->last_name)->toBe('Doe');
});

it('updates only last_name', function (): void {
    $response = $this->actingAs($this->user)->putJson('/api/v1/driver/profile', [
        'last_name' => 'Smith',
    ]);

    $response->assertOk()
        ->assertJson([
            'message' => 'Profile updated successfully.',
            'data'    => [
                'first_name' => 'John',
                'last_name'  => 'Smith',
            ],
        ]);

    $this->user->refresh();

    expect($this->user->first_name)->toBe('John')
        ->and($this->user->last_name)->toBe('Smith');
});

it('validates first_name is not empty string', function (): void {
    $response = $this->actingAs($this->user)->putJson('/api/v1/driver/profile', [
        'first_name' => '',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['first_name']);
});

it('validates last_name is not empty string', function (): void {
    $response = $this->actingAs($this->user)->putJson('/api/v1/driver/profile', [
        'last_name' => '',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['last_name']);
});

it('validates first_name max length', function (): void {
    $response = $this->actingAs($this->user)->putJson('/api/v1/driver/profile', [
        'first_name' => str_repeat('a', 256),
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['first_name']);
});

it('validates last_name max length', function (): void {
    $response = $this->actingAs($this->user)->putJson('/api/v1/driver/profile', [
        'last_name' => str_repeat('a', 256),
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['last_name']);
});

it('returns unauthorized without authentication', function (): void {
    $response = $this->putJson('/api/v1/driver/profile', [
        'first_name' => 'Jane',
    ]);

    $response->assertUnauthorized();
});

it('returns forbidden for rider role', function (): void {
    $rider = User::factory()->create([
        'first_name'        => 'Rider',
        'last_name'         => 'User',
        'role'              => UserRole::RIDER->value,
        'profile_step'      => ProfileStep::COMPLETED,
        'status'            => UserStatus::ACTIVE,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($rider)->putJson('/api/v1/driver/profile', [
        'first_name' => 'Jane',
    ]);

    $response->assertForbidden();
});
