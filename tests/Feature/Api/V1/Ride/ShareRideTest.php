<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

test('rider can share their ride', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    Notification::fake();

    actingAs($rider)
        ->postJson(route('api.v1.rider.rides.share', $ride->id), [
            'contact_name'  => 'John Doe',
            'contact_phone' => '+1234567890',
            'contact_email' => 'john@example.com',
            'message'       => 'Track my ride!',
        ])
        ->assertStatus(201)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.ride_id', $ride->id);
});

test('rider can share ride without email', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    Notification::fake();

    actingAs($rider)
        ->postJson(route('api.v1.rider.rides.share', $ride->id), [
            'contact_name'  => 'John Doe',
            'contact_phone' => '+1234567890',
        ])
        ->assertStatus(201);
});

test('rider cannot share another riders ride', function (): void {
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

    $ride = Ride::factory()->create(['rider_id' => $otherRider->id]);

    actingAs($rider)
        ->postJson(route('api.v1.rider.rides.share', $ride->id), [
            'contact_name'  => 'John Doe',
            'contact_phone' => '+1234567890',
        ])
        ->assertStatus(403);
});

test('driver cannot share a ride', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    actingAs($driver)
        ->postJson(route('api.v1.rider.rides.share', $ride->id), [
            'contact_name'  => 'John Doe',
            'contact_phone' => '+1234567890',
        ])
        ->assertStatus(403);
});

test('unauthenticated user cannot share ride', function (): void {
    $ride = Ride::factory()->create();

    postJson(route('api.v1.rider.rides.share', $ride->id), [
        'contact_name'  => 'John Doe',
        'contact_phone' => '+1234567890',
    ])
        ->assertStatus(401);
});

test('contact name is required', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    actingAs($rider)
        ->postJson(route('api.v1.rider.rides.share', $ride->id), [
            'contact_phone' => '+1234567890',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['contact_name']);
});

test('contact phone is required', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    actingAs($rider)
        ->postJson(route('api.v1.rider.rides.share', $ride->id), [
            'contact_name' => 'John Doe',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['contact_phone']);
});

test('contact email must be valid', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    actingAs($rider)
        ->postJson(route('api.v1.rider.rides.share', $ride->id), [
            'contact_name'  => 'John Doe',
            'contact_phone' => '+1234567890',
            'contact_email' => 'invalid-email',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['contact_email']);
});

test('message has max length', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $ride = Ride::factory()->create(['rider_id' => $rider->id]);

    actingAs($rider)
        ->postJson(route('api.v1.rider.rides.share', $ride->id), [
            'contact_name'  => 'John Doe',
            'contact_phone' => '+1234567890',
            'message'       => str_repeat('a', 501),
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['message']);
});
