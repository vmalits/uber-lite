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
            'message' => 'Avatar uploaded successfully.',
        ])
        ->assertJsonStructure([
            'data' => ['avatar_url'],
        ]);

    $user->refresh();

    expect($user->avatar_path)->toBeString()
        ->and($user->avatar_path)->toContain('avatars/'.$user->id.'/avatar.jpg');
});

it('rider with completed profile can upload avatar', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $file = UploadedFile::fake()->image('avatar.png');

    $response = $this->postJson('/api/v1/rider/avatar', [
        'avatar' => $file,
    ]);

    $response
        ->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Avatar uploaded successfully.',
        ])
        ->assertJsonStructure([
            'data' => ['avatar_url'],
        ]);

    $user->refresh();

    expect($user->avatar_path)->toBeString()
        ->and($user->avatar_path)->toContain('avatars/'.$user->id.'/avatar.png');
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

it('denies avatar upload for rider with incomplete profile', function (): void {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::PHONE_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->postJson('/api/v1/rider/avatar', [
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

it('denies avatar upload for non-rider', function (): void {
    if (! function_exists('imagejpeg')) {
        $this->markTestSkipped('GD extension with imagejpeg function is required for this test.');
    }
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $file = UploadedFile::fake()->image('avatar.jpg');

    $response = $this->postJson('/api/v1/rider/avatar', [
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

    // Test missing file
    $response = $this->postJson('/api/v1/driver/avatar', []);
    $response->assertUnprocessable()->assertJsonValidationErrors(['avatar']);

    // Test invalid file type
    $file = UploadedFile::fake()->create('document.pdf', 100);
    $response = $this->postJson('/api/v1/driver/avatar', ['avatar' => $file]);
    $response->assertUnprocessable()->assertJsonValidationErrors(['avatar']);

    // Test file too large
    $file = UploadedFile::fake()->image('large.jpg')->size(6000); // 6MB > 5MB
    $response = $this->postJson('/api/v1/driver/avatar', ['avatar' => $file]);
    $response->assertUnprocessable()->assertJsonValidationErrors(['avatar']);
});

it('overwrites existing avatar when uploading new one', function (): void {
    if (! function_exists('imagejpeg')) {
        $this->markTestSkipped('GD extension with imagejpeg function is required for this test.');
    }

    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $user->update(['avatar_path' => 'avatars/'.$user->id.'/old.jpg']);

    Sanctum::actingAs($user);

    $file = UploadedFile::fake()->image('new.jpg');

    $response = $this->postJson('/api/v1/driver/avatar', [
        'avatar' => $file,
    ]);

    $response->assertOk();

    $user->refresh();

    expect($user->avatar_path)->toContain('avatars/'.$user->id.'/avatar.jpg')
        ->and($user->avatar_path)->not()->toBe('avatars/'.$user->id.'/old.jpg');
});
