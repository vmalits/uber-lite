<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('rider can get timeline for completed ride', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride = Ride::factory()->create([
        'rider_id'     => $rider->id,
        'status'       => RideStatus::COMPLETED,
        'arrived_at'   => now()->subHours(2),
        'started_at'   => now()->subHours(2)->addMinutes(5),
        'completed_at' => now()->subHours(2)->addMinutes(30),
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/timeline");
    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.ride_id', $ride->id)
        ->assertJsonPath('data.current_status', 'completed');

    $events = $response->json('data.events');
    $statuses = array_column($events, 'status');

    expect($statuses)->toContain('pending', 'accepted', 'on_the_way', 'arrived', 'started', 'completed');
    expect($statuses)->not->toContain('scheduled', 'cancelled');
});

test('rider can get timeline for cancelled ride', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride = Ride::factory()->create([
        'rider_id'     => $rider->id,
        'status'       => RideStatus::CANCELLED,
        'cancelled_at' => now()->subMinutes(10),
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/timeline");
    $response->assertOk()
        ->assertJsonPath('data.current_status', 'cancelled');

    $events = $response->json('data.events');
    $statuses = array_column($events, 'status');

    expect($statuses)->toContain('pending', 'cancelled');
});

test('rider can get timeline for scheduled ride', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride = Ride::factory()->create([
        'rider_id'     => $rider->id,
        'status'       => RideStatus::SCHEDULED,
        'scheduled_at' => now()->addHours(2),
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/timeline");
    $response->assertOk()
        ->assertJsonPath('data.current_status', 'scheduled');

    $events = $response->json('data.events');
    $statuses = array_column($events, 'status');

    expect($statuses)->toContain('scheduled');
    expect($statuses)->not->toContain('completed', 'cancelled');
});

test('timeline includes timestamps for completed statuses', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $arrivedAt = now()->subHours(1);

    $ride = Ride::factory()->create([
        'rider_id'   => $rider->id,
        'status'     => RideStatus::ARRIVED,
        'arrived_at' => $arrivedAt,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/timeline");
    $response->assertOk();

    $events = $response->json('data.events');

    $arrivedEvent = array_values(array_filter($events, fn ($e) => $e['status'] === 'arrived'))[0] ?? null;
    expect($arrivedEvent)->not->toBeNull()
        ->and($arrivedEvent['timestamp'])->not->toBeNull();
});

test('rider cannot get timeline for another riders ride', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $otherRider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $otherRider->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $this->getJson("/api/v1/rider/rides/{$ride->id}/timeline")
        ->assertForbidden();
});

test('unauthenticated user cannot get ride timeline', function (): void {
    $ride = Ride::factory()->create([
        'status' => RideStatus::COMPLETED,
    ]);

    $this->getJson("/api/v1/rider/rides/{$ride->id}/timeline")
        ->assertUnauthorized();
});
