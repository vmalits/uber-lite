<?php

declare(strict_types=1);

use App\Actions\Rider\RebookRideAction;
use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('allows rider to rebook a completed ride', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $previousRide = Ride::factory()->completed()->create([
        'rider_id'            => $rider->id,
        'origin_address'      => 'Strada Ștefan cel Mare 1, Centru',
        'origin_lat'          => 47.0268,
        'origin_lng'          => 28.8416,
        'destination_address' => 'Strada Tighina 49, Botanica',
        'destination_lat'     => 46.9986,
        'destination_lng'     => 28.8574,
    ]);

    actingAs($rider)
        ->postJson(route('api.v1.rider.rides.rebook', $previousRide))
        ->assertStatus(201)
        ->assertJsonStructure([
            'success',
            'data' => [
                'id',
                'rider_id',
                'origin_address',
                'origin_lat',
                'origin_lng',
                'destination_address',
                'destination_lat',
                'destination_lng',
                'status',
                'estimated_price',
                'estimated_distance_km',
                'estimated_duration_min',
            ],
            'message',
        ])
        ->assertJsonPath('data.origin_address', $previousRide->origin_address)
        ->assertJsonPath('data.destination_address', $previousRide->destination_address);

    $this->assertDatabaseHas('rides', [
        'rider_id'            => $rider->id,
        'origin_address'      => $previousRide->origin_address,
        'destination_address' => $previousRide->destination_address,
        'status'              => RideStatus::PENDING->value,
    ]);
});

it('allows rider to rebook a cancelled ride', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $previousRide = Ride::factory()->cancelled()->create([
        'rider_id' => $rider->id,
    ]);

    actingAs($rider)
        ->postJson(route('api.v1.rider.rides.rebook', $previousRide))
        ->assertStatus(201);
});

it('denies rebooking an active ride', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $activeRide = Ride::factory()->active()->create([
        'rider_id' => $rider->id,
    ]);

    actingAs($rider)
        ->postJson(route('api.v1.rider.rides.rebook', $activeRide))
        ->assertStatus(403);
});

it('denies rebooking a pending ride', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $pendingRide = Ride::factory()->pending()->create([
        'rider_id' => $rider->id,
    ]);

    actingAs($rider)
        ->postJson(route('api.v1.rider.rides.rebook', $pendingRide))
        ->assertStatus(403);
});

it('denies rebooking another rider ride', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $otherRider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $previousRide = Ride::factory()->completed()->create([
        'rider_id' => $otherRider->id,
    ]);

    actingAs($rider)
        ->postJson(route('api.v1.rider.rides.rebook', $previousRide))
        ->assertStatus(403);
});

it('prevents rebooking when rider already has an active ride', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    Ride::factory()->active()->create([
        'rider_id' => $rider->id,
    ]);

    $previousRide = Ride::factory()->completed()->create([
        'rider_id' => $rider->id,
    ]);

    actingAs($rider)
        ->postJson(route('api.v1.rider.rides.rebook', $previousRide))
        ->assertStatus(422);
});

it('denies rebooking for unauthenticated user', function (): void {
    $ride = Ride::factory()->completed()->create();

    $this->postJson(route('api.v1.rider.rides.rebook', $ride))
        ->assertStatus(401);
});

it('denies rebooking for driver', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->completed()->create();

    actingAs($driver)
        ->postJson(route('api.v1.rider.rides.rebook', $ride))
        ->assertStatus(403);
});

it('rebook action creates ride with same origin and destination', function (): void {
    $rider = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $previousRide = Ride::factory()->completed()->create([
        'rider_id'            => $rider->id,
        'origin_address'      => 'Strada Ștefan cel Mare 1, Centru',
        'origin_lat'          => 47.0268,
        'origin_lng'          => 28.8416,
        'destination_address' => 'Strada Tighina 49, Botanica',
        'destination_lat'     => 46.9986,
        'destination_lng'     => 28.8574,
    ]);

    $action = app(RebookRideAction::class);
    $newRide = $action->handle($rider, $previousRide);

    expect($newRide)->toBeInstanceOf(Ride::class)
        ->and($newRide->rider_id)->toBe($rider->id)
        ->and($newRide->origin_address)->toBe($previousRide->origin_address)
        ->and($newRide->origin_lat)->toBe($previousRide->origin_lat)
        ->and($newRide->origin_lng)->toBe($previousRide->origin_lng)
        ->and($newRide->destination_address)->toBe($previousRide->destination_address)
        ->and($newRide->destination_lat)->toBe($previousRide->destination_lat)
        ->and($newRide->destination_lng)->toBe($previousRide->destination_lng)
        ->and($newRide->status)->toBe(RideStatus::PENDING)
        ->and($newRide->id)->not->toBe($previousRide->id);
});
