<?php

declare(strict_types=1);

use App\Enums\PaymentMethodType;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\PaymentMethod;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('rider can get payment methods', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    PaymentMethod::factory()->create([
        'user_id' => $rider->id,
    ]);

    actingAs($rider)
        ->getJson('/api/v1/rider/payment-methods')
        ->assertStatus(200)
        ->assertJsonPath('success', true);
});

test('payment methods are ordered by default first', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $defaultMethod = PaymentMethod::factory()->default()->create([
        'user_id'    => $rider->id,
        'created_at' => now()->subDay(),
    ]);

    $otherMethod = PaymentMethod::factory()->create([
        'user_id'    => $rider->id,
        'created_at' => now(),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/rider/payment-methods')
        ->assertStatus(200)
        ->assertJsonPath('data.0.id', $defaultMethod->id)
        ->assertJsonPath('data.0.is_default', true)
        ->assertJsonPath('data.1.id', $otherMethod->id);
});

test('payment methods only shows own methods', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    $otherRider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
    ]);

    PaymentMethod::factory()->create([
        'user_id' => $rider->id,
    ]);

    PaymentMethod::factory()->create([
        'user_id' => $otherRider->id,
    ]);

    actingAs($rider)
        ->getJson('/api/v1/rider/payment-methods')
        ->assertStatus(200)
        ->assertJsonCount(1, 'data');
});

test('unauthenticated user cannot access payment methods', function (): void {
    getJson('/api/v1/rider/payment-methods')
        ->assertStatus(401);
});

test('driver cannot access rider payment methods', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($driver)
        ->getJson('/api/v1/rider/payment-methods')
        ->assertStatus(403);
});
