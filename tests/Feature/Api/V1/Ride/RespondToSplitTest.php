<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideSplitStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\RideSplit;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

test('participant can accept split invitation', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    $split = RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'John Doe',
        'status'           => RideSplitStatus::PENDING,
    ]);

    $responder = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    actingAs($responder)
        ->postJson("/api/v1/ride/{$ride->id}/split/respond", [
            'split_id' => $split->id,
            'status'   => 'accepted',
        ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.status', 'accepted');

    $this->assertDatabaseHas('ride_splits', [
        'id'     => $split->id,
        'status' => RideSplitStatus::ACCEPTED->value,
    ]);

    expect($split->refresh()->responded_at)->not->toBeNull();
});

test('participant can decline split invitation', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    $split = RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'Jane Doe',
        'status'           => RideSplitStatus::PENDING,
    ]);

    $responder = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    actingAs($responder)
        ->postJson("/api/v1/ride/{$ride->id}/split/respond", [
            'split_id' => $split->id,
            'status'   => 'declined',
        ])
        ->assertOk()
        ->assertJsonPath('data.status', 'declined');

    $this->assertDatabaseHas('ride_splits', [
        'id'     => $split->id,
        'status' => RideSplitStatus::DECLINED->value,
    ]);
});

test('cannot respond to already responded split', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    $split = RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'John Doe',
        'status'           => RideSplitStatus::ACCEPTED,
    ]);

    $responder = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    actingAs($responder)
        ->postJson("/api/v1/ride/{$ride->id}/split/respond", [
            'split_id' => $split->id,
            'status'   => 'accepted',
        ])
        ->assertStatus(422);
});

test('split must belong to the ride', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);
    $otherRide = Ride::factory()->create();

    $split = RideSplit::factory()->create([
        'ride_id'          => $otherRide->id,
        'participant_name' => 'John Doe',
        'status'           => RideSplitStatus::PENDING,
    ]);

    $responder = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    actingAs($responder)
        ->postJson("/api/v1/ride/{$ride->id}/split/respond", [
            'split_id' => $split->id,
            'status'   => 'accepted',
        ])
        ->assertNotFound();
});

test('status must be accepted or declined', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    $split = RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'John Doe',
        'status'           => RideSplitStatus::PENDING,
    ]);

    $responder = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    actingAs($responder)
        ->postJson("/api/v1/ride/{$ride->id}/split/respond", [
            'split_id' => $split->id,
            'status'   => 'pending',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['status']);
});

test('split_id is required', function (): void {
    $ride = Ride::factory()->create();

    $responder = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    actingAs($responder)
        ->postJson("/api/v1/ride/{$ride->id}/split/respond", [
            'status' => 'accepted',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['split_id']);
});

test('unauthenticated user cannot respond to split', function (): void {
    $ride = Ride::factory()->create();

    postJson("/api/v1/ride/{$ride->id}/split/respond", [
        'split_id' => '01JKFAKE000000000000000000',
        'status'   => 'accepted',
    ])
        ->assertUnauthorized();
});
