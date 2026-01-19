<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\User;

it('returns paginated drivers list for admin', function () {
    $admin = User::factory()->create([
        'role' => UserRole::ADMIN,
    ]);
    User::factory()->count(3)->create(['role' => UserRole::DRIVER]);
    User::factory()->count(2)->create(['role' => UserRole::RIDER]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/drivers');

    $response->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'items' => [
                    '*' => [
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
                ],
                'pagination' => [
                    'total',
                    'per_page',
                    'current_page',
                    'last_page',
                ],
            ],
        ]);

    expect($response['data']['items'])->toHaveCount(3);
    collect($response['data']['items'])->each(fn ($user) => expect($user['role'])->toBe('driver'));
});

it('rejects non-admin user from drivers list', function () {
    $rider = User::factory()->create(['role' => UserRole::RIDER]);
    $response = $this->actingAs($rider, 'sanctum')
        ->getJson('/api/v1/admin/drivers');
    $response->assertForbidden();
});
