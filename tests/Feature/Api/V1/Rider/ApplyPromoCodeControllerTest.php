<?php

declare(strict_types=1);

use App\Enums\DiscountType;
use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\PromoCode;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('rider can apply fixed promo code to pending ride', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $promoCode = PromoCode::create([
        'code'           => 'TEST50',
        'title'          => 'Test Promo',
        'discount_type'  => DiscountType::FIXED,
        'discount_value' => 50,
        'is_active'      => true,
    ]);

    $ride = Ride::factory()->create([
        'rider_id'        => $rider->id,
        'status'          => RideStatus::PENDING,
        'estimated_price' => 200,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->postJson("/api/v1/rider/rides/{$ride->id}/promo-code", [
        'code' => 'TEST50',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.discount_amount', 50);

    $this->assertDatabaseHas('rides', [
        'id'              => $ride->id,
        'discount_amount' => 50,
        'promo_code_id'   => $promoCode->id,
    ]);

    $this->assertDatabaseHas('promo_code_usages', [
        'promo_code_id' => $promoCode->id,
        'user_id'       => $rider->id,
        'ride_id'       => $ride->id,
    ]);
});

test('rider cannot apply invalid promo code', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::PENDING,
    ]);

    Sanctum::actingAs($rider);

    $this->postJson("/api/v1/rider/rides/{$ride->id}/promo-code", [
        'code' => 'INVALID',
    ])
        ->assertStatus(422);
});

test('rider cannot apply promo code to completed ride', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $ride = Ride::factory()->create([
        'rider_id' => $rider->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $this->postJson("/api/v1/rider/rides/{$ride->id}/promo-code", [
        'code' => 'ANYCODE',
    ])
        ->assertForbidden();
});

test('unauthenticated user cannot apply promo code', function (): void {
    $ride = Ride::factory()->create([
        'status' => RideStatus::PENDING,
    ]);

    $this->postJson("/api/v1/rider/rides/{$ride->id}/promo-code", [
        'code' => 'TEST',
    ])
        ->assertUnauthorized();
});
