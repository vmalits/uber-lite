<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

it('returns paginated users list for admin', function () {
    $admin = User::factory()->create([
        'role' => UserRole::ADMIN,
    ]);
    User::factory()->count(5)->create(['role' => UserRole::RIDER]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/users');

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
    expect($response['data']['items'])->toBeArray();
});

it('filters users by role', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    User::factory()->count(3)->create(['role' => UserRole::RIDER]);
    User::factory()->count(2)->create(['role' => UserRole::DRIVER]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/users?filter[role]=driver');

    $response->assertOk();
    $items = $response['data']['items'];
    expect($items)->toHaveCount(2);
    collect($items)->each(fn ($user) => expect($user['role'])->toBe('driver'));
});

it('filters users by status', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    User::factory()->count(3)->create(['status' => UserStatus::ACTIVE]);
    User::factory()->count(2)->create(['status' => UserStatus::INACTIVE]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/users?filter[status]=inactive');

    $response->assertOk();
    $items = $response['data']['items'];
    expect($items)->toHaveCount(2);
    collect($items)->each(fn ($user) => expect($user['status'])->toBe('inactive'));
});

it('filters banned users', function () {
    $admin = User::factory()->create([
        'role'      => UserRole::ADMIN,
        'banned_at' => null,
    ]);
    User::factory()->count(3)->create(['banned_at' => now()]);
    User::factory()->count(2)->create(['banned_at' => null]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/users?filter[banned]=true');

    $response->assertOk();
    $items = $response['data']['items'];
    expect($items)->toHaveCount(3);
    collect($items)->each(fn ($user) => expect($user['banned_at'])->not()->toBeNull()
        ->and($user['banned_at'])->toHaveKeys(['human', 'string']));
});

it('filters users by phone', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $user1 = User::factory()->create(['phone' => '+37360000105']);
    $user2 = User::factory()->create(['phone' => '+37360000106']);
    User::factory()->create(['phone' => '+37370000107']);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/users?filter[phone]=+373600001');

    $response->assertOk();
    $items = $response['data']['items'];
    expect($items)->toHaveCount(2);
    $ids = collect($items)->pluck('id');
    expect($ids)->toContain($user1->id)->toContain($user2->id);
});

it('filters users by email', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $user = User::factory()->create(['email' => 'foo@example.com']);
    User::factory()->create(['email' => 'bar@example.com']);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/users?filter[email]=foo');

    $response->assertOk();
    $items = $response['data']['items'];
    expect($items)->toHaveCount(1)
        ->and($items[0]['id'])->toBe($user->id);
});

it('filters users by first_name', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $user = User::factory()->create(['first_name' => 'Johnathan']);
    User::factory()->create(['first_name' => 'Jane']);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/users?filter[first_name]=John');

    $response->assertOk();
    $items = $response['data']['items'];
    expect($items)->toHaveCount(1)
        ->and($items[0]['id'])->toBe($user->id);
});

it('filters users by last_name', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $user = User::factory()->create(['last_name' => 'Smithson']);
    User::factory()->create(['last_name' => 'Doe']);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/users?filter[last_name]=Smith');

    $response->assertOk();
    $items = $response['data']['items'];
    expect($items)->toHaveCount(1)
        ->and($items[0]['id'])->toBe($user->id);
});

it('sorts users by created_at desc by default', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN, 'created_at' => now()->subDays(10)]);
    $user1 = User::factory()->create(['created_at' => now()->subDays(2)]);
    $user2 = User::factory()->create(['created_at' => now()->subDays(1)]);
    $user3 = User::factory()->create(['created_at' => now()]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/users');

    $response->assertOk();
    $items = $response['data']['items'];

    expect($items[0]['id'])->toBe($user3->id)
        ->and($items[1]['id'])->toBe($user2->id)
        ->and($items[2]['id'])->toBe($user1->id);
});

it('rejects unauthorized request without token', function () {
    $response = $this->getJson('/api/v1/admin/users');
    $response->assertUnauthorized();
});

it('rejects non-admin user', function () {
    $rider = User::factory()->create(['role' => UserRole::RIDER]);
    $response = $this->actingAs($rider, 'sanctum')
        ->getJson('/api/v1/admin/users');
    $response->assertForbidden();
});

it('respects per_page parameter', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    User::factory()->count(10)->create();

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/users?per_page=5');

    $response->assertOk();
    expect($response['data']['items'])->toHaveCount(5)
        ->and($response['data']['pagination']['per_page'])->toBe(5);
});
