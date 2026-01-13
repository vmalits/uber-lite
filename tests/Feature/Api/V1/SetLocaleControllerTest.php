<?php

declare(strict_types=1);

use App\Enums\Locale;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can set locale to romanian', function (): void {
    /** @var User $user */
    $user = User::factory()->create(['locale' => Locale::EN->value]);
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/profile/locale', ['locale' => 'ro']);

    $response->assertOk()
        ->assertJson(['success' => true, 'data' => ['locale' => 'ro']]);

    expect($user->refresh()->locale)->toBe(Locale::RO);
});

it('can set locale to russian', function (): void {
    /** @var User $user */
    $user = User::factory()->create(['locale' => Locale::EN->value]);
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/profile/locale', ['locale' => 'ru']);

    $response->assertOk()
        ->assertJson(['success' => true, 'data' => ['locale' => 'ru']]);

    expect($user->refresh()->locale)->toBe(Locale::RU);
});

it('can set locale to english', function (): void {
    /** @var User $user */
    $user = User::factory()->create(['locale' => Locale::RO->value]);
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/profile/locale', ['locale' => 'en']);

    $response->assertOk()
        ->assertJson(['success' => true, 'data' => ['locale' => 'en']]);

    expect($user->refresh()->locale)->toBe(Locale::EN);
});

it('returns validation error for invalid locale', function (): void {
    /** @var User $user */
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/profile/locale', ['locale' => 'invalid']);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['locale']);
});

it('requires locale field', function (): void {
    /** @var User $user */
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/profile/locale', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['locale']);
});

it('returns 401 for unauthenticated user', function (): void {
    $response = $this->postJson('/api/v1/profile/locale', ['locale' => 'ro']);

    $response->assertUnauthorized();
});

it('returns 422 for invalid locale value', function (): void {
    /** @var User $user */
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/profile/locale', ['locale' => 'xx']);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['locale']);
});
