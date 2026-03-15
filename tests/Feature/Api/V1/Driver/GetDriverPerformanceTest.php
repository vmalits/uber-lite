<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

test('driver can get performance data', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($driver)
        ->getJson('/api/v1/driver/performance')
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonStructure([
            'data' => [
                'current_period' => [
                    'rides',
                    'earnings',
                    'tips',
                    'rating',
                    'completion_rate',
                    'cancelled_rides',
                    'online_hours',
                ],
                'previous_period' => [
                    'rides',
                    'earnings',
                    'tips',
                    'rating',
                    'completion_rate',
                    'cancelled_rides',
                    'online_hours',
                ],
                'comparison' => [
                    'percentile',
                    'avg_rides',
                    'avg_earnings',
                    'avg_rating',
                    'top_10_percent_rides',
                    'top_10_percent_earnings',
                ],
            ],
        ]);
});

test('driver can filter by period', function (): void {
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($driver)
        ->getJson('/api/v1/driver/performance?period=last_month')
        ->assertStatus(200)
        ->assertJsonPath('success', true);
});

test('unauthenticated user cannot access performance', function (): void {
    getJson('/api/v1/driver/performance')
        ->assertStatus(401);
});

test('rider cannot access driver performance', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/driver/performance')
        ->assertStatus(403);
});
