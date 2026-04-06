<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\DriverSchedule;
use App\Models\User;
use Illuminate\Contracts\Redis\Factory as RedisFactory;

use function Pest\Laravel\artisan;

it('brings driver online when schedule is active', function (): void {
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

    artisan('driver:sync-schedules')
        ->expectsOutputToContain('Sync complete:')
        ->assertSuccessful();

    $redis = app(RedisFactory::class)->connection();
    $state = $redis->get("driver:{$driver->id}:state");

    expect($state)->toBe('online');

    $redis->del("driver:{$driver->id}:state");
});

it('does not change status for offline driver outside schedule', function (): void {
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
        'start_time'  => '08:00',
        'end_time'    => '10:00',
        'enabled'     => true,
    ]);

    artisan('driver:sync-schedules')
        ->expectsOutputToContain('Sync complete:')
        ->assertSuccessful();

    $redis = app(RedisFactory::class)->connection();
    $state = $redis->get("driver:{$driver->id}:state");

    expect($state)->toBeNull();
});

it('respects manual override when bringing drivers online', function (): void {
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

    artisan('driver:sync-schedules')
        ->expectsOutputToContain('Sync complete:')
        ->assertSuccessful();

    $state = $redis->get("driver:{$driver->id}:state");
    expect($state)->toBeNull();

    $redis->del("driver:{$driver->id}:schedule_override");
});

it('sends driver offline when schedule ends', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $now = now();
    $currentDay = $now->dayOfWeekIso % 7;
    $endTime = $now->copy()->subSeconds(30)->format('H:i:s');
    $startTime = $now->copy()->subHours(8)->format('H:i:s');

    DriverSchedule::factory()->create([
        'driver_id'   => $driver->id,
        'day_of_week' => $currentDay,
        'start_time'  => $startTime,
        'end_time'    => $endTime,
        'enabled'     => true,
    ]);

    $redis = app(RedisFactory::class)->connection();
    $redis->sadd('drivers:online', $driver->id);
    $redis->set("driver:{$driver->id}:state", 'online');

    artisan('driver:sync-schedules')
        ->assertSuccessful();

    $state = $redis->get("driver:{$driver->id}:state");
    expect($state)->toBeNull();

    $redis->srem('drivers:online', $driver->id);
});

it('completes successfully with no schedules', function (): void {
    artisan('driver:sync-schedules')
        ->expectsOutputToContain('Sync complete: 0 went online, 0 went offline')
        ->assertSuccessful();
});
