<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\FavoriteRoute;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('rider can list favorite routes', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    FavoriteRoute::factory()->count(3)->create(['user_id' => $rider->id]);

    Sanctum::actingAs($rider);

    $this->getJson('/api/v1/rider/routes/favorite')
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonCount(3, 'data.items');
});

test('rider only sees own favorite routes', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $otherRider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    FavoriteRoute::factory()->count(2)->create(['user_id' => $rider->id]);
    FavoriteRoute::factory()->count(5)->create(['user_id' => $otherRider->id]);

    Sanctum::actingAs($rider);

    $this->getJson('/api/v1/rider/routes/favorite')
        ->assertOk()
        ->assertJsonCount(2, 'data.items');
});

test('unauthenticated user cannot list favorite routes', function (): void {
    $this->getJson('/api/v1/rider/routes/favorite')
        ->assertUnauthorized();
});

test('driver cannot list favorite routes', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $this->getJson('/api/v1/rider/routes/favorite')
        ->assertForbidden();
});

test('rider can get a single favorite route', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var FavoriteRoute $route */
    $route = FavoriteRoute::factory()->create(['user_id' => $rider->id]);

    Sanctum::actingAs($rider);

    $this->getJson("/api/v1/rider/routes/favorite/{$route->id}")
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.id', $route->id)
        ->assertJsonPath('data.name', $route->name);
});

test('rider cannot view another rider\'s favorite route', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $otherRider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var FavoriteRoute $route */
    $route = FavoriteRoute::factory()->create(['user_id' => $otherRider->id]);

    Sanctum::actingAs($rider);

    $this->getJson("/api/v1/rider/routes/favorite/{$route->id}")
        ->assertForbidden();
});

test('returns 404 for non-existent favorite route', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($rider);

    $this->getJson('/api/v1/rider/routes/favorite/non-existent-id')
        ->assertNotFound();
});

test('rider can update a favorite route', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var FavoriteRoute $route */
    $route = FavoriteRoute::factory()->create([
        'user_id' => $rider->id,
        'name'    => 'Old Name',
    ]);

    Sanctum::actingAs($rider);

    $this->putJson("/api/v1/rider/routes/favorite/{$route->id}", [
        'name' => 'New Name',
    ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.name', 'New Name');

    $this->assertDatabaseHas('favorite_routes', [
        'id'   => $route->id,
        'name' => 'New Name',
    ]);
});

test('rider can update route type', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var FavoriteRoute $route */
    $route = FavoriteRoute::factory()->create([
        'user_id' => $rider->id,
        'type'    => 'work',
    ]);

    Sanctum::actingAs($rider);

    $this->putJson("/api/v1/rider/routes/favorite/{$route->id}", [
        'type' => 'home',
    ])
        ->assertOk()
        ->assertJsonPath('data.type', 'home');
});

test('rider cannot update another rider\'s favorite route', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $otherRider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var FavoriteRoute $route */
    $route = FavoriteRoute::factory()->create(['user_id' => $otherRider->id]);

    Sanctum::actingAs($rider);

    $this->putJson("/api/v1/rider/routes/favorite/{$route->id}", [
        'name' => 'Hacked Name',
    ])
        ->assertForbidden();
});

test('update validates coordinate bounds', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var FavoriteRoute $route */
    $route = FavoriteRoute::factory()->create(['user_id' => $rider->id]);

    Sanctum::actingAs($rider);

    $this->putJson("/api/v1/rider/routes/favorite/{$route->id}", [
        'origin_lat' => 999,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['origin_lat']);
});

test('rider can delete a favorite route', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var FavoriteRoute $route */
    $route = FavoriteRoute::factory()->create(['user_id' => $rider->id]);

    Sanctum::actingAs($rider);

    $this->deleteJson("/api/v1/rider/routes/favorite/{$route->id}")
        ->assertOk()
        ->assertJsonPath('success', true);

    $this->assertDatabaseMissing('favorite_routes', ['id' => $route->id]);
});

test('rider cannot delete another rider\'s favorite route', function (): void {
    $rider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    $otherRider = User::factory()->verified()->create([
        'role'         => UserRole::RIDER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    /** @var FavoriteRoute $route */
    $route = FavoriteRoute::factory()->create(['user_id' => $otherRider->id]);

    Sanctum::actingAs($rider);

    $this->deleteJson("/api/v1/rider/routes/favorite/{$route->id}")
        ->assertForbidden();

    $this->assertDatabaseHas('favorite_routes', ['id' => $route->id]);
});

test('unauthenticated user cannot delete favorite route', function (): void {
    /** @var FavoriteRoute $route */
    $route = FavoriteRoute::factory()->create();

    $this->deleteJson("/api/v1/rider/routes/favorite/{$route->id}")
        ->assertUnauthorized();
});
