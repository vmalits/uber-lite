<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\DriverSchedule;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('driver can create a schedule', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->postJson('/api/v1/driver/schedule', [
        'day_of_week' => 1,
        'start_time'  => '08:00',
        'end_time'    => '17:00',
        'enabled'     => true,
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.day_of_week', 1)
        ->assertJsonPath('data.start_time', '08:00')
        ->assertJsonPath('data.end_time', '17:00')
        ->assertJsonPath('data.enabled', true);

    expect(DriverSchedule::where('driver_id', $driver->id)->count())->toBe(1);
});

test('driver can list their schedules', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    DriverSchedule::factory()->create([
        'driver_id'   => $driver->id,
        'day_of_week' => 1,
    ]);
    DriverSchedule::factory()->create([
        'driver_id'   => $driver->id,
        'day_of_week' => 2,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/schedule');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonCount(2, 'data');
});

test('driver can update their schedule', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $schedule = DriverSchedule::factory()->create([
        'driver_id'  => $driver->id,
        'start_time' => '08:00',
        'end_time'   => '17:00',
    ]);

    Sanctum::actingAs($driver);

    $response = $this->putJson("/api/v1/driver/schedule/{$schedule->id}", [
        'start_time' => '09:00',
        'end_time'   => '18:00',
    ]);

    $response->assertOk()
        ->assertJsonPath('data.start_time', '09:00')
        ->assertJsonPath('data.end_time', '18:00');
});

test('driver can delete their schedule', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $schedule = DriverSchedule::factory()->create([
        'driver_id' => $driver->id,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->deleteJson("/api/v1/driver/schedule/{$schedule->id}");

    $response->assertOk()
        ->assertJsonPath('success', true);

    expect(DriverSchedule::find($schedule->id))->toBeNull();
});

test('driver cannot update another drivers schedule', function (): void {
    $driver1 = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
    $driver2 = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $schedule = DriverSchedule::factory()->create([
        'driver_id' => $driver2->id,
    ]);

    Sanctum::actingAs($driver1);

    $response = $this->putJson("/api/v1/driver/schedule/{$schedule->id}", [
        'start_time' => '09:00',
    ]);

    $response->assertForbidden();
});

test('schedule validation requires valid day of week', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->postJson('/api/v1/driver/schedule', [
        'day_of_week' => 7,
        'start_time'  => '08:00',
        'end_time'    => '17:00',
    ]);

    $response->assertUnprocessable();
});

test('schedule validation requires end_time after start_time', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->postJson('/api/v1/driver/schedule', [
        'day_of_week' => 1,
        'start_time'  => '17:00',
        'end_time'    => '08:00',
    ]);

    $response->assertUnprocessable();
});

test('rider cannot access driver schedule endpoints', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $this->getJson('/api/v1/driver/schedule')->assertForbidden();
    $this->postJson('/api/v1/driver/schedule', [])->assertForbidden();
});

test('guest cannot access driver schedule endpoints', function (): void {
    $this->getJson('/api/v1/driver/schedule')->assertUnauthorized();
    $this->postJson('/api/v1/driver/schedule', [])->assertUnauthorized();
});
