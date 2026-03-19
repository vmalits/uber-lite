<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\PaymentMethod;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('rider can add a payment method', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/rider/payment-methods', [
        'type'         => 'card',
        'provider'     => 'stripe',
        'token'        => 'pm_test_token',
        'last_four'    => '4242',
        'card_brand'   => 'visa',
        'expiry_month' => 12,
        'expiry_year'  => 2027,
        'holder_name'  => 'John Doe',
    ]);

    $response->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.last_four', '4242')
        ->assertJsonPath('data.card_brand', 'visa')
        ->assertJsonPath('data.is_default', true);

    $this->assertDatabaseHas('payment_methods', [
        'user_id'   => $user->id,
        'last_four' => '4242',
    ]);
});

test('first payment method is set as default', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/rider/payment-methods', [
        'type'       => 'card',
        'provider'   => 'stripe',
        'token'      => 'pm_test_1',
        'last_four'  => '1111',
        'card_brand' => 'visa',
    ])->assertCreated()
        ->assertJsonPath('data.is_default', true);
});

test('second payment method is not set as default', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    PaymentMethod::factory()->create([
        'user_id'    => $user->id,
        'is_default' => true,
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/rider/payment-methods', [
        'type'       => 'card',
        'provider'   => 'stripe',
        'token'      => 'pm_test_2',
        'last_four'  => '2222',
        'card_brand' => 'mastercard',
    ])->assertCreated()
        ->assertJsonPath('data.is_default', false);
});

test('unauthenticated user cannot add payment method', function (): void {
    $this->postJson('/api/v1/rider/payment-methods', [
        'type'       => 'card',
        'provider'   => 'stripe',
        'token'      => 'pm_test',
        'last_four'  => '4242',
        'card_brand' => 'visa',
    ])->assertUnauthorized();
});

test('driver cannot add payment method', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $this->postJson('/api/v1/rider/payment-methods', [
        'type'       => 'card',
        'provider'   => 'stripe',
        'token'      => 'pm_test',
        'last_four'  => '4242',
        'card_brand' => 'visa',
    ])->assertForbidden();
});

test('validation fails with missing required fields', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/rider/payment-methods', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['type', 'provider', 'token', 'last_four', 'card_brand']);
});

test('validation fails with invalid type', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/rider/payment-methods', [
        'type'       => 'crypto',
        'provider'   => 'stripe',
        'token'      => 'pm_test',
        'last_four'  => '4242',
        'card_brand' => 'visa',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['type']);
});

test('validation fails with invalid provider', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/rider/payment-methods', [
        'type'       => 'card',
        'provider'   => 'square',
        'token'      => 'pm_test',
        'last_four'  => '4242',
        'card_brand' => 'visa',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors(['provider']);
});

test('apple pay can be added', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $this->postJson('/api/v1/rider/payment-methods', [
        'type'       => 'apple_pay',
        'provider'   => 'stripe',
        'token'      => 'apple_pay_token',
        'last_four'  => '0000',
        'card_brand' => 'apple_pay',
    ])->assertCreated()
        ->assertJsonPath('data.type', 'apple_pay');
});
