<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\RideStatus;
use App\Enums\UserRole;
use App\Models\Ride;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('driver can get available rides', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Ride::factory()->count(3)->create([
        'status' => RideStatus::PENDING,
    ]);

    Ride::factory()->create([
        'status' => RideStatus::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/driver/rides/available');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'items',
                'pagination' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                ],
            ],
        ])
        ->assertJsonCount(3, 'data.items')
        ->assertJsonPath('data.pagination.total', 3);
});

it('driver available rides are paginated', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Ride::factory()->count(20)->create([
        'status' => RideStatus::PENDING,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/driver/rides/available?per_page=10');

    $response->assertOk()
        ->assertJsonCount(10, 'data.items')
        ->assertJsonPath('data.pagination.total', 20)
        ->assertJsonPath('data.pagination.per_page', 10)
        ->assertJsonPath('data.pagination.last_page', 2);
});

it('guest cannot get available rides', function (): void {
    $response = $this->getJson('/api/v1/driver/rides/available');
    $response->assertUnauthorized();
});

it('driver with incomplete profile cannot get available rides', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::EMAIL_VERIFIED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/driver/rides/available');
    $response->assertForbidden();
});

it('rider cannot get available rides', function (): void {
    /** @var User $user */
    $user = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/driver/rides/available');
    $response->assertForbidden();
});
