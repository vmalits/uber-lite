<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Facades\Cache;

uses()->group('unit');

beforeEach(function (): void {
    // Use array cache driver for tests to avoid Redis dependency
    config(['cache.default' => 'array']);
    Cache::flush();
});

it('retrieves user from database and caches it', function (): void {
    $user = User::factory()->create();

    // First call - should hit database
    $retrievedUser = auth()->getProvider()->retrieveById($user->id);

    expect($retrievedUser)->not->toBeNull()
        ->and($retrievedUser->id)->toBe($user->id)
        ->and($retrievedUser->phone)->toBe($user->phone);

    // Verify cache was set
    expect(Cache::has("user:{$user->id}"))->toBeTrue();

    // Verify cached data
    $cachedUser = Cache::get("user:{$user->id}");
    expect($cachedUser)->not->toBeNull()
        ->and($cachedUser->id)->toBe($user->id);
});

it('retrieves user from cache on second call', function (): void {
    $user = User::factory()->create();

    // First call - caches the user
    $firstRetrieval = auth()->getProvider()->retrieveById($user->id);
    expect($firstRetrieval)->not->toBeNull();

    // Manually verify cache exists
    expect(Cache::has("user:{$user->id}"))->toBeTrue();

    // Second call - should return from cache (faster, no DB query)
    $secondRetrieval = auth()->getProvider()->retrieveById($user->id);

    expect($secondRetrieval)->not->toBeNull()
        ->and($secondRetrieval->id)->toBe($user->id)
        ->and(Cache::has("user:{$user->id}"))->toBeTrue();
});

it('cache is invalidated when user is updated', function (): void {
    $user = User::factory()->create(['first_name' => 'John']);

    // Cache the user
    auth()->getProvider()->retrieveById($user->id);
    expect(Cache::has("user:{$user->id}"))->toBeTrue();

    // Update user - should invalidate cache via UserObserver
    $user->update(['first_name' => 'Jane']);

    // Cache should be cleared
    expect(Cache::has("user:{$user->id}"))->toBeFalse();

    // Next retrieval should fetch fresh data from database
    $freshUser = auth()->getProvider()->retrieveById($user->id);
    expect($freshUser->first_name)->toBe('Jane');
});

it('cache is invalidated when user is deleted', function (): void {
    $user = User::factory()->create();

    // Cache the user
    auth()->getProvider()->retrieveById($user->id);
    expect(Cache::has("user:{$user->id}"))->toBeTrue();

    // Delete user - should invalidate cache via UserObserver
    $userId = $user->id;
    $user->delete();

    // Cache should be cleared
    expect(Cache::has("user:{$userId}"))->toBeFalse();
});

it('returns null for non-existent user', function (): void {
    $nonExistentId = 'non-existent-id';

    $user = auth()->getProvider()->retrieveById($nonExistentId);

    expect($user)->toBeNull();
});

it('uses configured cache TTL', function (): void {
    $user = User::factory()->create();
    $cacheTtl = config('auth.providers.users.cache_ttl', 3600);

    // Retrieve user to cache it
    auth()->getProvider()->retrieveById($user->id);

    // Verify cache exists
    expect(Cache::has("user:{$user->id}"))->toBeTrue();

    // Note: We can't easily test exact TTL without time manipulation,
    // but we verify the cache was created with our provider
    $cachedUser = Cache::get("user:{$user->id}");
    expect($cachedUser)->not->toBeNull()
        ->and($cachedUser->id)->toBe($user->id);
});

it('provider extends EloquentUserProvider and inherits all methods', function (): void {
    $user = User::factory()->create(['password' => bcrypt('password123')]);

    // Test retrieveByCredentials (not cached)
    $retrievedUser = auth()->getProvider()->retrieveByCredentials([
        'phone' => $user->phone,
    ]);

    expect($retrievedUser)->not->toBeNull()
        ->and($retrievedUser->id)->toBe($user->id);

    // Test validateCredentials
    $isValid = auth()->getProvider()->validateCredentials($user, [
        'password' => 'password123',
    ]);

    expect($isValid)->toBeTrue();

    // Test with wrong password
    $isInvalid = auth()->getProvider()->validateCredentials($user, [
        'password' => 'wrong-password',
    ]);

    expect($isInvalid)->toBeFalse();
});

it('cache key follows correct pattern', function (): void {
    $user = User::factory()->create();

    auth()->getProvider()->retrieveById($user->id);

    // Verify cache key pattern matches UserObserver pattern
    $cacheKey = "user:{$user->id}";
    expect(Cache::has($cacheKey))->toBeTrue();
});
