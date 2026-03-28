<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Admin;

use App\Enums\AchievementCategory;
use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\Achievement;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

function createAdminForAchievement(): User
{
    return User::factory()->create([
        'role'              => UserRole::ADMIN,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
}

function validAchievementPayload(array $overrides = []): array
{
    return array_merge([
        'name'          => 'First Ride',
        'key'           => 'first_ride',
        'description'   => 'Complete your first ride',
        'icon'          => 'trophy',
        'category'      => AchievementCategory::COMMON->value,
        'target_value'  => 1,
        'points_reward' => 50,
        'metadata'      => null,
        'is_active'     => true,
    ], $overrides);
}

test('admin can create achievement', function (): void {
    $admin = createAdminForAchievement();

    actingAs($admin)
        ->postJson('/api/v1/admin/achievements', validAchievementPayload())
        ->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.name', 'First Ride')
        ->assertJsonPath('data.key', 'first_ride')
        ->assertJsonPath('data.category', 'common')
        ->assertJsonPath('data.target_value', 1)
        ->assertJsonPath('data.points_reward', 50)
        ->assertJsonPath('data.is_active', true);
});

test('admin can create achievement with metadata', function (): void {
    $admin = createAdminForAchievement();

    actingAs($admin)
        ->postJson('/api/v1/admin/achievements', validAchievementPayload([
            'key'      => 'with_metadata',
            'metadata' => ['bonus_credits' => 10],
        ]))
        ->assertCreated()
        ->assertJsonPath('data.metadata.bonus_credits', 10);
});

test('create achievement validates required fields', function (string $field, mixed $value): void {
    $admin = createAdminForAchievement();

    actingAs($admin)
        ->postJson('/api/v1/admin/achievements', validAchievementPayload([$field => $value]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([$field]);
})->with([
    'name is required'           => ['name', ''],
    'key is required'            => ['key', ''],
    'category is required'       => ['category', ''],
    'category must be valid'     => ['category', 'invalid'],
    'target_value is required'   => ['target_value', null],
    'target_value must be >= 1'  => ['target_value', 0],
    'points_reward is required'  => ['points_reward', null],
    'points_reward must be >= 0' => ['points_reward', -1],
]);

test('create achievement key must be unique', function (): void {
    $admin = createAdminForAchievement();
    Achievement::factory()->create(['key' => 'unique_key']);

    actingAs($admin)
        ->postJson('/api/v1/admin/achievements', validAchievementPayload(['key' => 'unique_key']))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['key']);
});

test('create achievement key must be lowercase with underscores', function (): void {
    $admin = createAdminForAchievement();

    actingAs($admin)
        ->postJson('/api/v1/admin/achievements', validAchievementPayload(['key' => 'Invalid Key!']))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['key']);
});

test('non-admin cannot create achievement', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->postJson('/api/v1/admin/achievements', validAchievementPayload())
        ->assertForbidden();
});

test('unauthenticated user cannot create achievement', function (): void {
    postJson('/api/v1/admin/achievements', validAchievementPayload())
        ->assertUnauthorized();
});

test('admin can list achievements', function (): void {
    $admin = createAdminForAchievement();
    Achievement::factory()->count(3)->create();

    actingAs($admin)
        ->getJson('/api/v1/admin/achievements')
        ->assertOk()
        ->assertJsonStructure([
            'success',
            'data' => [
                'items' => [
                    '*' => ['id', 'name', 'key', 'category', 'target_value', 'points_reward', 'is_active'],
                ],
                'pagination' => ['total', 'per_page', 'current_page', 'last_page'],
            ],
        ])
        ->assertJsonCount(3, 'data.items');
});

test('admin achievements list is empty initially', function (): void {
    $admin = createAdminForAchievement();

    actingAs($admin)
        ->getJson('/api/v1/admin/achievements')
        ->assertOk()
        ->assertJsonCount(0, 'data.items');
});

test('non-admin cannot list achievements', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    actingAs($rider)
        ->getJson('/api/v1/admin/achievements')
        ->assertForbidden();
});

test('admin can show achievement', function (): void {
    $admin = createAdminForAchievement();
    $achievement = Achievement::factory()->create([
        'name' => 'Century Driver',
        'key'  => 'century_driver',
    ]);

    actingAs($admin)
        ->getJson("/api/v1/admin/achievements/{$achievement->id}")
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.name', 'Century Driver')
        ->assertJsonPath('data.key', 'century_driver');
});

test('non-admin cannot show achievement', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
    $achievement = Achievement::factory()->create();

    actingAs($rider)
        ->getJson("/api/v1/admin/achievements/{$achievement->id}")
        ->assertForbidden();
});

test('admin can update achievement', function (): void {
    $admin = createAdminForAchievement();
    $achievement = Achievement::factory()->create([
        'name' => 'Old Name',
        'key'  => 'old_key',
    ]);

    actingAs($admin)
        ->putJson("/api/v1/admin/achievements/{$achievement->id}", validAchievementPayload([
            'name' => 'Updated Name',
            'key'  => 'updated_key',
        ]))
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.name', 'Updated Name')
        ->assertJsonPath('data.key', 'updated_key');
});

test('update achievement validates required fields', function (string $field, mixed $value): void {
    $admin = createAdminForAchievement();
    $achievement = Achievement::factory()->create();

    actingAs($admin)
        ->putJson("/api/v1/admin/achievements/{$achievement->id}", validAchievementPayload([$field => $value]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors([$field]);
})->with([
    'name is required'          => ['name', ''],
    'key is required'           => ['key', ''],
    'category is required'      => ['category', ''],
    'target_value must be >= 1' => ['target_value', 0],
]);

test('update achievement key must be unique ignoring self', function (): void {
    $admin = createAdminForAchievement();
    $achievement = Achievement::factory()->create(['key' => 'my_key']);
    Achievement::factory()->create(['key' => 'other_key']);

    actingAs($admin)
        ->putJson("/api/v1/admin/achievements/{$achievement->id}", validAchievementPayload(['key' => 'other_key']))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['key']);

    actingAs($admin)
        ->putJson("/api/v1/admin/achievements/{$achievement->id}", validAchievementPayload(['key' => 'my_key']))
        ->assertOk();
});

test('non-admin cannot update achievement', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
    $achievement = Achievement::factory()->create();

    actingAs($rider)
        ->putJson("/api/v1/admin/achievements/{$achievement->id}", validAchievementPayload())
        ->assertForbidden();
});

test('admin can delete achievement', function (): void {
    $admin = createAdminForAchievement();
    $achievement = Achievement::factory()->create();

    actingAs($admin)
        ->deleteJson("/api/v1/admin/achievements/{$achievement->id}")
        ->assertOk()
        ->assertJsonPath('success', true);

    expect(Achievement::where('id', $achievement->id)->exists())->toBeFalse();
});

test('non-admin cannot delete achievement', function (): void {
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
    $achievement = Achievement::factory()->create();

    actingAs($rider)
        ->deleteJson("/api/v1/admin/achievements/{$achievement->id}")
        ->assertForbidden();
});

test('admin can filter achievements by category', function (): void {
    $admin = createAdminForAchievement();
    Achievement::factory()->create(['category' => AchievementCategory::DRIVER]);
    Achievement::factory()->create(['category' => AchievementCategory::RIDER]);
    Achievement::factory()->create(['category' => AchievementCategory::COMMON]);

    actingAs($admin)
        ->getJson('/api/v1/admin/achievements?filter[category]=driver')
        ->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.category', 'driver');
});

test('admin can filter achievements by is_active', function (): void {
    $admin = createAdminForAchievement();
    Achievement::factory()->create(['is_active' => true]);
    Achievement::factory()->create(['is_active' => false]);

    actingAs($admin)
        ->getJson('/api/v1/admin/achievements?filter[is_active]=false')
        ->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.is_active', false);
});

test('admin can filter achievements by key', function (): void {
    $admin = createAdminForAchievement();
    Achievement::factory()->create(['key' => 'first_ride']);
    Achievement::factory()->create(['key' => 'century_driver']);

    actingAs($admin)
        ->getJson('/api/v1/admin/achievements?filter[key]=first')
        ->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.key', 'first_ride');
});

test('admin can sort achievements by points_reward', function (): void {
    $admin = createAdminForAchievement();
    Achievement::factory()->create(['name' => 'Low', 'points_reward' => 10]);
    Achievement::factory()->create(['name' => 'High', 'points_reward' => 100]);

    actingAs($admin)
        ->getJson('/api/v1/admin/achievements?sort=points_reward')
        ->assertOk()
        ->assertJsonPath('data.items.0.name', 'Low')
        ->assertJsonPath('data.items.1.name', 'High');
});
