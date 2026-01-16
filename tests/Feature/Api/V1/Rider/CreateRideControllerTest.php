<?php

declare(strict_types=1);

use App\Actions\Rider\CreateRideAction;
use App\Data\Rider\CreateRideData;
use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('allows rider with completed profile to create a ride', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $payload = [
        'origin_address'      => 'bd. Ștefan cel Mare și Sfânt, 1, Chișinău',
        'origin_lat'          => 47.0105,
        'origin_lng'          => 28.8638,
        'destination_address' => 'str. Mihai Eminescu, 50, Chișinău',
        'destination_lat'     => 47.0225,
        'destination_lng'     => 28.8353,
    ];

    $data = new CreateRideData(...$payload);

    $action = app(CreateRideAction::class);
    $ride = $action->handle($user, $data);

    expect($ride)->toBeInstanceOf(App\Models\Ride::class)
        ->and($ride->rider_id)->toBe($user->id)
        ->and($ride->status)->toBe(RideStatus::PENDING);

    $this->assertDatabaseHas('rides', [
        'rider_id' => $user->id,
        'status'   => RideStatus::PENDING->value,
    ]);
});

it('denies ride creation for rider with incomplete profile', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);
    Sanctum::actingAs($user);
    $payload = [
        'origin_address'      => 'bd. Ștefan cel Mare și Sfânt, 1, Chișinău',
        'origin_lat'          => 47.0105,
        'origin_lng'          => 28.8638,
        'destination_address' => 'str. Mihai Eminescu, 50, Chișinău',
        'destination_lat'     => 47.0225,
        'destination_lng'     => 28.8353,
    ];
    $response = $this->postJson('/api/v1/rider/rides', $payload);
    $response->assertForbidden();
});

it('denies ride creation for non-rider', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
    Sanctum::actingAs($user);
    $payload = [
        'origin_address'      => 'bd. Ștefan cel Mare și Sfânt, 1, Chișinău',
        'origin_lat'          => 47.0105,
        'origin_lng'          => 28.8638,
        'destination_address' => 'str. Mihai Eminescu, 50, Chișinău',
        'destination_lat'     => 47.0225,
        'destination_lng'     => 28.8353,
    ];
    $response = $this->postJson('/api/v1/rider/rides', $payload);
    $response->assertForbidden();
});

it('denies ride creation for unauthenticated user', function () {
    $payload = [
        'origin_address'      => 'bd. Ștefan cel Mare și Sfânt, 1, Chișinău',
        'origin_lat'          => 47.0105,
        'origin_lng'          => 28.8638,
        'destination_address' => 'str. Mihai Eminescu, 50, Chișinău',
        'destination_lat'     => 47.0225,
        'destination_lng'     => 28.8353,
    ];
    $response = $this->postJson('/api/v1/rider/rides', $payload);
    $response->assertUnauthorized();
});

it('validates required fields', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/rider/rides', []);
    $response->assertUnprocessable()
        ->assertJsonValidationErrors([
            'origin_address',
            'origin_lat',
            'origin_lng',
            'destination_address',
            'destination_lat',
            'destination_lng',
        ]);
});

it('prevents creating a second active ride', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    App\Models\Ride::factory()->create([
        'rider_id' => $user->id,
        'status'   => RideStatus::PENDING->value,
    ]);

    $payload = [
        'origin_address'      => 'bd. Ștefan cel Mare și Sfânt, 1, Chișinău',
        'origin_lat'          => 47.0105,
        'origin_lng'          => 28.8638,
        'destination_address' => 'str. Mihai Eminescu, 50, Chișinău',
        'destination_lat'     => 47.0225,
        'destination_lng'     => 28.8353,
    ];

    $response = $this->postJson('/api/v1/rider/rides', $payload);
    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['ride']);
});
