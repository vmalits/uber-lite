<?php

declare(strict_types=1);

use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns paginated rides list for admin', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    Ride::factory()->count(5)->create();

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/rides');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'items' => [
                    '*' => [
                        'id',
                        'rider_id',
                        'driver_id',
                        'origin_address',
                        'destination_address',
                        'status',
                        'price',
                        'created_at' => ['human', 'string'],
                        'updated_at' => ['human', 'string'],
                    ],
                ],
                'pagination' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                ],
            ],
        ]);
});

it('filters rides by status', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    Ride::factory()->count(3)->create(['status' => RideStatus::COMPLETED]);
    Ride::factory()->count(2)->create(['status' => RideStatus::CANCELLED]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/rides?filter[status]=completed');

    $response->assertOk();
    $items = $response['data']['items'];
    expect($items)->toHaveCount(3);
    collect($items)->each(fn ($ride) => expect($ride['status'])->toBe('completed'));
});

it('filters rides by rider_id', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $rider = User::factory()->create(['role' => UserRole::RIDER]);
    Ride::factory()->count(2)->create(['rider_id' => $rider->id]);
    Ride::factory()->create();

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson("/api/v1/admin/rides?filter[rider_id]={$rider->id}");

    $response->assertOk();
    $items = $response['data']['items'];
    expect($items)->toHaveCount(2);
    collect($items)->each(fn ($ride) => expect($ride['rider_id'])->toBe($rider->id));
});

it('filters rides by driver_id', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $driver = User::factory()->create(['role' => UserRole::DRIVER]);
    Ride::factory()->count(2)->create(['driver_id' => $driver->id]);
    Ride::factory()->create(['driver_id' => null]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson("/api/v1/admin/rides?filter[driver_id]={$driver->id}");

    $response->assertOk();
    $items = $response['data']['items'];
    expect($items)->toHaveCount(2);
    collect($items)->each(fn ($ride) => expect($ride['driver_id'])->toBe($driver->id));
});

it('sorts rides by price', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $ride1 = Ride::factory()->create(['price' => 100]);
    $ride2 = Ride::factory()->create(['price' => 200]);
    $ride3 = Ride::factory()->create(['price' => 150]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/rides?sort=-price');

    $response->assertOk();
    $items = $response['data']['items'];
    expect($items[0]['id'])->toBe($ride2->id)
        ->and($items[1]['id'])->toBe($ride3->id)
        ->and($items[2]['id'])->toBe($ride1->id);
});

it('rejects unauthorized request without token', function () {
    $response = $this->getJson('/api/v1/admin/rides');
    $response->assertUnauthorized();
});

it('rejects non-admin user', function () {
    $rider = User::factory()->create(['role' => UserRole::RIDER]);
    $response = $this->actingAs($rider, 'sanctum')
        ->getJson('/api/v1/admin/rides');
    $response->assertForbidden();
});
