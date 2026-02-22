<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Rider;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('returns fare breakdown for a ride', function () {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $ride = Ride::factory()->create([
        'rider_id'               => $rider->id,
        'base_fee'               => 15.0,
        'price_per_km'           => 8.0,
        'price_per_minute'       => 2.0,
        'estimated_distance_km'  => 5.0,
        'estimated_duration_min' => 10.0,
        'estimated_price'        => 75,
    ]);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/fare-breakdown");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'data'    => [
                'base_fare'              => 15.0,
                'distance_fare'          => 40.0,
                'duration_fare'          => 20.0,
                'total'                  => 75.0,
                'estimated_distance_km'  => 5.0,
                'estimated_duration_min' => 10.0,
                'price_per_km'           => 8.0,
                'price_per_minute'       => 2.0,
            ],
        ]);
});

it('denies fare breakdown for other user ride', function () {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $otherRider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $ride = Ride::factory()->create([
        'rider_id' => $otherRider->id,
    ]);

    $response = $this->getJson("/api/v1/rider/rides/{$ride->id}/fare-breakdown");

    $response->assertForbidden();
});
