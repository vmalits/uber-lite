<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\User;
use App\Models\UserRideStreak;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
});

test('driver can get streak info', function (): void {
    UserRideStreak::factory()->withStreak(7)->create([
        'user_id' => $this->driver->id,
    ]);

    Sanctum::actingAs($this->driver);

    $response = $this->getJson('/api/v1/driver/streak');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.current_streak', 7)
        ->assertJsonPath('data.level', 'silver')
        ->assertJsonPath('data.discount_percent', 10);
});

test('driver can get streak info with no streak', function (): void {
    Sanctum::actingAs($this->driver);

    $response = $this->getJson('/api/v1/driver/streak');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.current_streak', 0)
        ->assertJsonPath('data.level', 'none')
        ->assertJsonPath('data.discount_percent', 0);
});

test('unauthorized user cannot get driver streak', function (): void {
    $response = $this->getJson('/api/v1/driver/streak');

    $response->assertUnauthorized();
});

test('rider cannot access driver streak', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $this->getJson('/api/v1/driver/streak')
        ->assertForbidden();
});

test('streak returns correct discount for bronze level', function (): void {
    UserRideStreak::factory()->withStreak(3)->create([
        'user_id' => $this->driver->id,
    ]);

    Sanctum::actingAs($this->driver);

    $response = $this->getJson('/api/v1/driver/streak');

    $response->assertOk()
        ->assertJsonPath('data.level', 'bronze')
        ->assertJsonPath('data.discount_percent', 5);
});

test('streak returns correct discount for gold level', function (): void {
    UserRideStreak::factory()->withStreak(14)->create([
        'user_id' => $this->driver->id,
    ]);

    Sanctum::actingAs($this->driver);

    $response = $this->getJson('/api/v1/driver/streak');

    $response->assertOk()
        ->assertJsonPath('data.level', 'gold')
        ->assertJsonPath('data.discount_percent', 15);
});

test('streak returns correct discount for platinum level', function (): void {
    UserRideStreak::factory()->withStreak(30)->create([
        'user_id' => $this->driver->id,
    ]);

    Sanctum::actingAs($this->driver);

    $response = $this->getJson('/api/v1/driver/streak');

    $response->assertOk()
        ->assertJsonPath('data.level', 'platinum')
        ->assertJsonPath('data.discount_percent', 20);
});
