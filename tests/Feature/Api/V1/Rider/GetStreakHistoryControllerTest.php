<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use App\Models\UserRideStreak;
use Carbon\Carbon;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
});

test('rider can get streak history', function (): void {
    UserRideStreak::factory()->withStreak(7)->create([
        'user_id' => $this->rider->id,
    ]);

    // Create rides for the last 7 days
    for ($i = 0; $i < 7; $i++) {
        Ride::factory()->create([
            'rider_id'     => $this->rider->id,
            'status'       => RideStatus::COMPLETED,
            'completed_at' => Carbon::now()->subDays($i),
        ]);
    }

    Sanctum::actingAs($this->rider);

    $response = $this->getJson('/api/v1/rider/streak/history');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.current_streak', 7)
        ->assertJsonStructure([
            'success',
            'data' => [
                'current_streak',
                'longest_streak',
                'history' => [
                    '*' => [
                        'streak_count',
                        'date',
                        'ride_completed',
                    ],
                ],
            ],
        ]);
});

test('rider can get streak history with custom days parameter', function (): void {
    UserRideStreak::factory()->withStreak(3)->create([
        'user_id' => $this->rider->id,
    ]);

    Ride::factory()->create([
        'rider_id'     => $this->rider->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => Carbon::now(),
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson('/api/v1/rider/streak/history?days=7');

    $response->assertOk()
        ->assertJsonPath('success', true);

    // Should have 7 days of history
    $history = $response->json('data.history');
    expect(count($history))->toBe(7);
});

test('streak history shows correct streak count progression', function (): void {
    Ride::factory()->create([
        'rider_id'     => $this->rider->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => Carbon::now()->subDays(2),
    ]);

    Ride::factory()->create([
        'rider_id'     => $this->rider->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => Carbon::now()->subDays(1),
    ]);

    Ride::factory()->create([
        'rider_id'     => $this->rider->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => Carbon::now(),
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson('/api/v1/rider/streak/history?days=7');

    $response->assertOk();

    $history = collect($response->json('data.history'));

    // Find today's entry (last in the array)
    $todayEntry = $history->last();
    expect($todayEntry['ride_completed'])->toBeTrue()
        ->and($todayEntry['streak_count'])->toBe(3);
});

test('streak history resets when day is missed', function (): void {
    Ride::factory()->create([
        'rider_id'     => $this->rider->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => Carbon::now()->subDays(3),
    ]);

    // Skip day 2 (no ride)
    Ride::factory()->create([
        'rider_id'     => $this->rider->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => Carbon::now()->subDays(1),
    ]);

    Ride::factory()->create([
        'rider_id'     => $this->rider->id,
        'status'       => RideStatus::COMPLETED,
        'completed_at' => Carbon::now(),
    ]);

    Sanctum::actingAs($this->rider);

    $response = $this->getJson('/api/v1/rider/streak/history?days=7');

    $response->assertOk();

    $history = collect($response->json('data.history'));

    // Today should have streak of 2 (yesterday and today, but the day before yesterday was missed)
    $todayEntry = $history->last();
    expect($todayEntry['streak_count'])->toBe(2);
});

test('rider can get streak history with no streak data', function (): void {
    Sanctum::actingAs($this->rider);

    $response = $this->getJson('/api/v1/rider/streak/history');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.current_streak', 0)
        ->assertJsonPath('data.longest_streak', 0);
});

test('unauthorized user cannot get streak history', function (): void {
    $response = $this->getJson('/api/v1/rider/streak/history');

    $response->assertUnauthorized();
});

test('validation fails for invalid days parameter', function (): void {
    Sanctum::actingAs($this->rider);

    $response = $this->getJson('/api/v1/rider/streak/history?days=5');

    $response->assertUnprocessable();
});

test('validation fails for days parameter exceeding max', function (): void {
    Sanctum::actingAs($this->rider);

    $response = $this->getJson('/api/v1/rider/streak/history?days=100');

    $response->assertUnprocessable();
});
