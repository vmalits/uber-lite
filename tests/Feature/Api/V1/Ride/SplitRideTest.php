<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\RideSplit;
use App\Models\User;
use App\Notifications\Ride\RideSplitNotification;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

test('rider can split their ride', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    Notification::fake();

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split", [
            'participants' => [
                [
                    'name'  => 'John Doe',
                    'email' => 'john@example.com',
                    'phone' => '+1234567890',
                    'share' => 50,
                ],
            ],
            'note' => 'Split the ride!',
        ])
        ->assertStatus(201)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.ride_id', $ride->id)
        ->assertJsonStructure([
            'data' => [
                'ride_id',
                'invitations',
            ],
        ]);

    Notification::assertSentOnDemand(RideSplitNotification::class);

    $this->assertDatabaseHas('ride_splits', [
        'ride_id'           => $ride->id,
        'participant_name'  => 'John Doe',
        'participant_email' => 'john@example.com',
        'participant_phone' => '+1234567890',
    ]);
});

test('rider can split ride without email', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    Notification::fake();

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split", [
            'participants' => [
                ['name' => 'John Doe'],
            ],
        ])
        ->assertStatus(201);

    $this->assertDatabaseHas('ride_splits', [
        'ride_id'          => $ride->id,
        'participant_name' => 'John Doe',
    ]);
});

test('rider cannot split another riders ride', function (): void {
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

    $ride = Ride::factory()->create(['rider_id' => $otherRider->id]);

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split", [
            'participants' => [
                ['name' => 'John Doe'],
            ],
        ])
        ->assertStatus(403);
});

test('driver cannot split a ride', function (): void {
    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    actingAs($driver)
        ->postJson("/api/v1/ride/{$ride->id}/split", [
            'participants' => [
                ['name' => 'John Doe'],
            ],
        ])
        ->assertStatus(403);
});

test('unauthenticated user cannot split ride', function (): void {
    $ride = Ride::factory()->create();

    postJson("/api/v1/ride/{$ride->id}/split", [
        'participants' => [
            ['name' => 'John Doe'],
        ],
    ])
        ->assertStatus(401);
});

test('participants is required', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split", [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['participants']);
});

test('participant name is required', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split", [
            'participants' => [
                ['email' => 'test@example.com'],
            ],
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['participants.0.name']);
});

test('participant email must be valid', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split", [
            'participants' => [
                [
                    'name'  => 'John Doe',
                    'email' => 'invalid-email',
                ],
            ],
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['participants.0.email']);
});

test('note has max length', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split", [
            'participants' => [
                ['name' => 'John Doe'],
            ],
            'note' => str_repeat('a', 501),
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['note']);
});

test('can split ride with multiple participants', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    Notification::fake();

    actingAs($rider)
        ->postJson("/api/v1/ride/{$ride->id}/split", [
            'participants' => [
                ['name' => 'Alice', 'email' => 'alice@example.com', 'share' => 33.33],
                ['name' => 'Bob', 'email' => 'bob@example.com', 'share' => 33.33],
                ['name' => 'Charlie', 'email' => 'charlie@example.com', 'share' => 33.34],
            ],
        ])
        ->assertStatus(201)
        ->assertJsonCount(3, 'data.invitations');

    $this->assertEquals(3, RideSplit::where('ride_id', $ride->id)->count());
});
