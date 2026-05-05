<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideSplitStatus;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\RideSplit;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

test('rider can cancel pending splits', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    $split1 = RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'John Doe',
        'status'           => RideSplitStatus::PENDING,
    ]);

    $split2 = RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'Jane Doe',
        'status'           => RideSplitStatus::PENDING,
    ]);

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split/cancel")
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.ride_id', $ride->id)
        ->assertJsonPath('data.cancelled_count', 2)
        ->assertJsonCount(2, 'data.cancelled_splits');

    $this->assertDatabaseHas('ride_splits', [
        'id'     => $split1->id,
        'status' => RideSplitStatus::CANCELLED->value,
    ]);

    $this->assertDatabaseHas('ride_splits', [
        'id'     => $split2->id,
        'status' => RideSplitStatus::CANCELLED->value,
    ]);
});

test('rider can cancel only pending splits, not already responded ones', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    $pendingSplit = RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'John Doe',
        'status'           => RideSplitStatus::PENDING,
    ]);

    $acceptedSplit = RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'Jane Doe',
        'status'           => RideSplitStatus::ACCEPTED,
    ]);

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split/cancel")
        ->assertOk()
        ->assertJsonPath('data.cancelled_count', 1)
        ->assertJsonPath('data.cancelled_splits.0.id', $pendingSplit->id);

    $this->assertDatabaseHas('ride_splits', [
        'id'     => $pendingSplit->id,
        'status' => RideSplitStatus::CANCELLED->value,
    ]);

    $this->assertDatabaseHas('ride_splits', [
        'id'     => $acceptedSplit->id,
        'status' => RideSplitStatus::ACCEPTED->value,
    ]);
});

test('cannot cancel splits when ride is started', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::STARTED,
    ]);

    RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'John Doe',
        'status'           => RideSplitStatus::PENDING,
    ]);

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split/cancel")
        ->assertStatus(422)
        ->assertJsonPath('success', false)
        ->assertJsonPath('message', 'Cannot cancel splits after the ride has started.');
});

test('cannot cancel splits when ride is completed', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'John Doe',
        'status'           => RideSplitStatus::PENDING,
    ]);

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split/cancel")
        ->assertStatus(422)
        ->assertJsonPath('message', 'Cannot cancel splits for a completed or cancelled ride.');
});

test('cannot cancel splits when ride is cancelled', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::CANCELLED,
    ]);

    RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'John Doe',
        'status'           => RideSplitStatus::PENDING,
    ]);

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split/cancel")
        ->assertStatus(422)
        ->assertJsonPath('message', 'Cannot cancel splits for a completed or cancelled ride.');
});

test('cannot cancel splits when no pending splits exist', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'John Doe',
        'status'           => RideSplitStatus::ACCEPTED,
    ]);

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split/cancel")
        ->assertStatus(422)
        ->assertJsonPath('message', 'No pending split invitations found for this ride.');
});

test('rider cannot cancel another rider splits', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var User $otherRider */
    $otherRider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $otherRider->id,
        'status'   => RideStatus::PENDING,
    ]);

    RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'John Doe',
        'status'           => RideSplitStatus::PENDING,
    ]);

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split/cancel")
        ->assertForbidden();
});

test('driver cannot cancel ride splits', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'driver_id' => $driver->id,
        'status'    => RideStatus::PENDING,
    ]);

    RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'John Doe',
        'status'           => RideSplitStatus::PENDING,
    ]);

    actingAs($driver)
        ->postJson("/api/v1/ride/{$ride->id}/split/cancel")
        ->assertForbidden();
});

test('unauthenticated user cannot cancel ride splits', function (): void {
    $ride = Ride::factory()->create([
        'status' => RideStatus::PENDING,
    ]);

    postJson("/api/v1/ride/{$ride->id}/split/cancel")
        ->assertUnauthorized();
});

test('can cancel splits for accepted ride status', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::ACCEPTED,
    ]);

    $split = RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'John Doe',
        'status'           => RideSplitStatus::PENDING,
    ]);

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split/cancel")
        ->assertOk()
        ->assertJsonPath('data.cancelled_count', 1);

    $this->assertDatabaseHas('ride_splits', [
        'id'     => $split->id,
        'status' => RideSplitStatus::CANCELLED->value,
    ]);
});

test('can cancel splits for on_the_way ride status', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::ON_THE_WAY,
    ]);

    $split = RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'John Doe',
        'status'           => RideSplitStatus::PENDING,
    ]);

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split/cancel")
        ->assertOk()
        ->assertJsonPath('data.cancelled_count', 1);

    $this->assertDatabaseHas('ride_splits', [
        'id'     => $split->id,
        'status' => RideSplitStatus::CANCELLED->value,
    ]);
});

test('can cancel splits for arrived ride status', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::ARRIVED,
    ]);

    $split = RideSplit::factory()->create([
        'ride_id'          => $ride->id,
        'participant_name' => 'John Doe',
        'status'           => RideSplitStatus::PENDING,
    ]);

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split/cancel")
        ->assertOk()
        ->assertJsonPath('data.cancelled_count', 1);

    $this->assertDatabaseHas('ride_splits', [
        'id'     => $split->id,
        'status' => RideSplitStatus::CANCELLED->value,
    ]);
});
