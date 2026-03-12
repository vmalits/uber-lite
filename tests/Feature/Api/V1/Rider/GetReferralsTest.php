<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    $this->endpoint = '/api/v1/rider/referrals';
});

test('rider can get their referrals', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'          => UserRole::RIDER,
        'profile_step'  => ProfileStep::COMPLETED,
        'referral_code' => 'ABC12345',
    ]);

    // Create referred users
    $referredUsers = User::factory()->count(3)->create([
        'referred_by' => $rider->id,
        'referred_at' => now()->subDays(1),
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson($this->endpoint);

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'items',
                'pagination' => [
                    'current_page',
                    'per_page',
                    'total',
                    'last_page',
                ],
            ],
        ])
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.pagination.total', 3);
});

test('referrals include has_completed_ride status', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'          => UserRole::RIDER,
        'profile_step'  => ProfileStep::COMPLETED,
        'referral_code' => 'ABC12345',
    ]);

    /** @var User $referredWithRide */
    $referredWithRide = User::factory()->create([
        'referred_by' => $rider->id,
        'referred_at' => now()->subDays(2),
        'first_name'  => 'John',
        'last_name'   => 'Doe',
    ]);

    // Create a completed ride for the referred user
    Ride::factory()->create([
        'rider_id' => $referredWithRide->id,
        'status'   => RideStatus::COMPLETED,
    ]);

    /** @var User $referredWithoutRide */
    $referredWithoutRide = User::factory()->create([
        'referred_by' => $rider->id,
        'referred_at' => now()->subDays(1),
        'first_name'  => 'Jane',
        'last_name'   => 'Smith',
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson($this->endpoint);

    $response->assertOk();

    $items = $response->json('data.items');

    $johnReferral = collect($items)->firstWhere('first_name', 'John');
    $janeReferral = collect($items)->firstWhere('first_name', 'Jane');

    expect($johnReferral['has_completed_ride'])->toBeTrue()
        ->and($janeReferral['has_completed_ride'])->toBeFalse();
});

test('rider can paginate referrals', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'          => UserRole::RIDER,
        'profile_step'  => ProfileStep::COMPLETED,
        'referral_code' => 'ABC12345',
    ]);

    // Create 25 referred users
    User::factory()->count(25)->create([
        'referred_by' => $rider->id,
        'referred_at' => now()->subDays(1),
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson($this->endpoint.'?per_page=10');

    $response->assertOk()
        ->assertJsonPath('data.pagination.per_page', 10)
        ->assertJsonPath('data.pagination.total', 25)
        ->assertJsonPath('data.pagination.last_page', 3);
});

test('rider with no referrals gets empty list', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson($this->endpoint);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.pagination.total', 0)
        ->assertJsonPath('data.items', []);
});

test('referrals are sorted by referred_at descending', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'          => UserRole::RIDER,
        'profile_step'  => ProfileStep::COMPLETED,
        'referral_code' => 'ABC12345',
    ]);

    $old = User::factory()->create([
        'referred_by' => $rider->id,
        'referred_at' => now()->subDays(5),
    ]);

    $new = User::factory()->create([
        'referred_by' => $rider->id,
        'referred_at' => now(),
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson($this->endpoint);

    $response->assertOk();

    $referrals = $response->json('data.items');

    expect($referrals)->toHaveCount(2)
        ->and($referrals[0]['id'])->toBe($new->id)
        ->and($referrals[1]['id'])->toBe($old->id);
});

test('guest cannot get referrals', function (): void {
    $response = $this->getJson($this->endpoint);

    $response->assertUnauthorized();
});

test('driver cannot access rider referrals', function (): void {
    /** @var User $driver */
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->getJson($this->endpoint);

    $response->assertForbidden();
});

test('referrals only show users referred by current rider', function (): void {
    /** @var User $rider */
    $rider = User::factory()->verified()->create([
        'role'          => UserRole::RIDER,
        'profile_step'  => ProfileStep::COMPLETED,
        'referral_code' => 'ABC12345',
    ]);

    /** @var User $otherRider */
    $otherRider = User::factory()->verified()->create([
        'role'          => UserRole::RIDER,
        'profile_step'  => ProfileStep::COMPLETED,
        'referral_code' => 'XYZ98765',
    ]);

    // Create referral for current rider
    User::factory()->create([
        'referred_by' => $rider->id,
        'referred_at' => now()->subDays(1),
    ]);

    // Create referral for other rider
    User::factory()->create([
        'referred_by' => $otherRider->id,
        'referred_at' => now()->subDays(1),
    ]);

    Sanctum::actingAs($rider);

    $response = $this->getJson($this->endpoint);

    $response->assertOk()
        ->assertJsonPath('data.pagination.total', 1);
});
