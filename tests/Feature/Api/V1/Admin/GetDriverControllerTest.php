<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns driver details for admin', function () {
    $admin = User::factory()->create([
        'role' => UserRole::ADMIN,
    ]);
    $driver = User::factory()->create([
        'role' => UserRole::DRIVER,
    ]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson("/api/v1/admin/drivers/{$driver->id}");

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'user' => [
                    'id',
                    'first_name',
                    'last_name',
                    'phone',
                    'email',
                    'avatar_urls',
                    'role',
                    'locale',
                    'profile_step',
                    'status',
                    'phone_verified_at',
                    'email_verified_at',
                    'last_login_at',
                    'banned_at',
                    'created_at' => ['human', 'string'],
                    'updated_at' => ['human', 'string'],
                ],
                'stats' => [
                    'total_rides',
                    'completed_rides',
                    'cancelled_rides',
                    'average_rating',
                    'total_earned',
                ],
            ],
        ]);

    expect($response['data']['user']['id'])->toBe($driver->id)
        ->and($response['data']['user']['role'])->toBe('driver')
        ->and($response['data']['stats'])->toBeArray();
});

it('returns 404 for non-existent driver', function () {
    $admin = User::factory()->create([
        'role' => UserRole::ADMIN,
    ]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/drivers/non-existent-id');

    $response->assertNotFound();
});

it('returns 404 when querying non-driver user', function () {
    $admin = User::factory()->create([
        'role' => UserRole::ADMIN,
    ]);
    $rider = User::factory()->create([
        'role' => UserRole::RIDER,
    ]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson("/api/v1/admin/drivers/{$rider->id}");

    $response->assertNotFound();
});

it('rejects unauthorized request without token', function () {
    $driver = User::factory()->create([
        'role' => UserRole::DRIVER,
    ]);

    $response = $this->getJson("/api/v1/admin/drivers/{$driver->id}");
    $response->assertUnauthorized();
});

it('rejects non-admin user', function () {
    $rider = User::factory()->create(['role' => UserRole::RIDER]);
    $driver = User::factory()->create([
        'role' => UserRole::DRIVER,
    ]);

    $response = $this->actingAs($rider, 'sanctum')
        ->getJson("/api/v1/admin/drivers/{$driver->id}");
    $response->assertForbidden();
});

it('returns driver statistics correctly', function () {
    $admin = User::factory()->create([
        'role' => UserRole::ADMIN,
    ]);
    $driver = User::factory()->create([
        'role' => UserRole::DRIVER,
    ]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson("/api/v1/admin/drivers/{$driver->id}");

    $response->assertOk();
    expect($response['data']['stats']['total_rides'])->toBeInt()
        ->and($response['data']['stats']['completed_rides'])->toBeInt()
        ->and($response['data']['stats']['cancelled_rides'])->toBeInt()
        ->and($response['data']['stats']['average_rating'])->toBeNumeric()
        ->and($response['data']['stats']['total_earned'])->toBeNumeric();
});
