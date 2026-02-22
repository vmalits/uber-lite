<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Jobs\Rider\ActivateScheduledRideJob;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;

it('allows rider to schedule a ride', function () {
    Queue::fake();

    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $scheduledAt = now()->addHours(2);

    $payload = [
        'origin_address'      => 'bd. Ștefan cel Mare și Sfânt, 1, Chișinău',
        'origin_lat'          => 47.0105,
        'origin_lng'          => 28.8638,
        'destination_address' => 'str. Mihai Eminescu, 50, Chișinău',
        'destination_lat'     => 47.0225,
        'destination_lng'     => 28.8353,
        'scheduled_at'        => $scheduledAt->toDateTimeString(),
    ];

    $response = $this->postJson('/api/v1/rider/rides/scheduled', $payload);

    $response->assertCreated()
        ->assertJsonPath('data.status', RideStatus::SCHEDULED->value)
        ->assertJsonPath('data.scheduled_at.string', $scheduledAt->toDateTimeString());

    $this->assertDatabaseHas('rides', [
        'rider_id'     => $user->id,
        'status'       => RideStatus::SCHEDULED->value,
        'scheduled_at' => $scheduledAt->toDateTimeString(),
    ]);

    Queue::assertPushed(ActivateScheduledRideJob::class, function ($job) use ($response) {
        return $job->rideId === $response->json('data.id');
    });
});

it('validates that scheduled_at is in the future', function () {
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
        'scheduled_at'        => now()->subHour()->toDateTimeString(),
    ];

    $response = $this->postJson('/api/v1/rider/rides/scheduled', $payload);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['scheduled_at']);
});

it('denies scheduling for rider with incomplete profile', function () {
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $payload = [
        'origin_address'      => 'Origin',
        'origin_lat'          => 47,
        'origin_lng'          => 28,
        'destination_address' => 'Destination',
        'destination_lat'     => 47,
        'destination_lng'     => 28,
        'scheduled_at'        => now()->addHour()->toDateTimeString(),
    ];

    $response = $this->postJson('/api/v1/rider/rides/scheduled', $payload);

    $response->assertForbidden();
});
