<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $user = User::factory()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $user->phone_verified_at = now();
    $user->email_verified_at = now();
    $user->save();
});

test('can add favorite location', function (): void {
    actingAs(User::first())
        ->postJson(route('api.v1.rider.favorites.store'), [
            'name'    => 'Дом',
            'lat'     => 47.010,
            'lng'     => 28.863,
            'address' => 'Strada Stefan cel Mare 123, Chisinau',
        ])
        ->assertStatus(200)
        ->assertJsonStructure([
            'success',
            'data' => [
                'favorite' => [
                    'id',
                    'name',
                    'lat',
                    'lng',
                    'address',
                    'created_at' => ['human', 'string'],
                    'updated_at' => ['human', 'string'],
                ],
            ],
            'message',
        ]);
});

test('validation fails for required fields', function (): void {
    actingAs(User::first())
        ->postJson(route('api.v1.rider.favorites.store'), [
            'address' => 'Some address',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'lat', 'lng']);
});
