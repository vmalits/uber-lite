<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;

it('driver with completed profile can upload avatar', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->postJson('/api/v1/driver/avatar', [
        'avatar' => $file,
    ]);

    $response
        ->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Avatar upload processing started.',
        ])
        ->assertJsonStructure([
            'data' => ['processing', 'sizes'],
        ]);

    expect($response->json('data.processing'))->toBeTrue()
        ->and($response->json('data.sizes'))->toBeArray();
});

it('denies avatar upload for driver with incomplete profile', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->postJson('/api/v1/driver/avatar', [
        'avatar' => $file,
    ]);

    $response->assertForbidden();
});

it('denies avatar upload for non-driver', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->postJson('/api/v1/driver/avatar', [
        'avatar' => $file,
    ]);

    $response->assertForbidden();
});

it('denies avatar upload for unauthenticated user', function (): void {
    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->postJson('/api/v1/driver/avatar', [
        'avatar' => $file,
    ]);

    $response->assertUnauthorized();
});

it('validates avatar file requirements', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/driver/avatar', []);
    $response->assertUnprocessable()->assertJsonValidationErrors(['avatar']);

    $file = UploadedFile::fake()->create('document.pdf', 100);
    $response = $this->postJson('/api/v1/driver/avatar', ['avatar' => $file]);
    $response->assertUnprocessable()->assertJsonValidationErrors(['avatar']);

    $file = UploadedFile::fake()->image('large.jpg')->size(6000);
    $response = $this->postJson('/api/v1/driver/avatar', ['avatar' => $file]);
    $response->assertUnprocessable()->assertJsonValidationErrors(['avatar']);
});

it('overwrites existing avatar when uploading new one', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
        'avatar_paths' => [
            'sm' => 'avatars/test/avatar_sm.webp',
            'md' => 'avatars/test/avatar_md.webp',
            'lg' => 'avatars/test/avatar_lg.webp',
        ],
    ]);

    Sanctum::actingAs($user);

    $file = UploadedFile::fake()->image('new.jpg');

    $response = $this->postJson('/api/v1/driver/avatar', [
        'avatar' => $file,
    ]);

    $response->assertOk();

    expect($response->json('data.processing'))->toBeTrue();
});
