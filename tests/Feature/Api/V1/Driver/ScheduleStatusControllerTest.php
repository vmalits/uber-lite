<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\DriverSchedule;
use App\Models\User;
use Illuminate\Contracts\Redis\Factory as RedisFactory;
use Laravel\Sanctum\Sanctum;

test('driver can get schedule status with active slot', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $now = now();
    $currentDay = $now->dayOfWeekIso % 7;
    $currentTime = $now->format('H:i:s');

    DriverSchedule::factory()->create([
        'driver_id'   => $driver->id,
        'day_of_week' => $currentDay,
        'start_time'  => '00:00',
        'end_time'    => '23:59',
        'enabled'     => true,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/schedule/status');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.has_active_slot', true)
        ->assertJsonPath('data.active_slot.day_of_week', $currentDay)
        ->assertJsonPath('data.manual_override', false);
});

test('driver gets no active slot when outside schedule', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $now = now();
    $currentDay = $now->dayOfWeekIso % 7;
    $yesterday = ($currentDay + 6) % 7;

    DriverSchedule::factory()->create([
        'driver_id'   => $driver->id,
        'day_of_week' => $yesterday,
        'start_time'  => '00:00',
        'end_time'    => '23:59',
        'enabled'     => true,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/schedule/status');

    $response->assertOk()
        ->assertJsonPath('data.has_active_slot', false)
        ->assertJsonPath('data.active_slot', null);
});

test('driver gets next slot from future schedule', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $now = now();
    $currentDay = $now->dayOfWeekIso % 7;
    $tomorrow = ($currentDay + 1) % 7;

    DriverSchedule::factory()->create([
        'driver_id'   => $driver->id,
        'day_of_week' => $tomorrow,
        'start_time'  => '08:00',
        'end_time'    => '17:00',
        'enabled'     => true,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/schedule/status');

    $response->assertOk()
        ->assertJsonPath('data.has_active_slot', false)
        ->assertJsonPath('data.next_slot.day_of_week', $tomorrow);
});

test('schedule status shows manual override when set', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $now = now();
    $currentDay = $now->dayOfWeekIso % 7;

    DriverSchedule::factory()->create([
        'driver_id'   => $driver->id,
        'day_of_week' => $currentDay,
        'start_time'  => '00:00',
        'end_time'    => '23:59',
        'enabled'     => true,
    ]);

    $redis = app(RedisFactory::class)->connection();
    $redis->setex("driver:{$driver->id}:schedule_override", 3600, '1');

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/schedule/status');

    $response->assertOk()
        ->assertJsonPath('data.manual_override', true)
        ->assertJsonPath('data.has_active_slot', true);

    $redis->del("driver:{$driver->id}:schedule_override");
});

test('driver with no schedules gets empty status', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson('/api/v1/driver/schedule/status');

    $response->assertOk()
        ->assertJsonPath('data.has_active_slot', false)
        ->assertJsonPath('data.active_slot', null)
        ->assertJsonPath('data.next_slot', null)
        ->assertJsonPath('data.manual_override', false);
});

test('unauthenticated user cannot get schedule status', function (): void {
    $this->getJson('/api/v1/driver/schedule/status')->assertUnauthorized();
});

test('rider cannot get schedule status', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $this->getJson('/api/v1/driver/schedule/status')->assertForbidden();
});
