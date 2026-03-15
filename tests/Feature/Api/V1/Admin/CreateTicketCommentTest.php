<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\SupportTicket;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

test('admin can create ticket comment', function (): void {
    /** @var User $admin */
    $admin = User::factory()->create([
        'role'              => UserRole::ADMIN,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var User $user */
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var SupportTicket $ticket */
    $ticket = SupportTicket::factory()->create([
        'user_id' => $user->id,
    ]);

    actingAs($admin)
        ->postJson("/api/v1/admin/tickets/{$ticket->id}/comments", [
            'message' => 'Admin response to ticket',
        ])
        ->assertStatus(201)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.message', 'Admin response to ticket');
});

test('admin can comment on any users ticket', function (): void {
    /** @var User $admin */
    $admin = User::factory()->create([
        'role'              => UserRole::ADMIN,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var User $driver */
    $driver = User::factory()->create([
        'role'              => UserRole::DRIVER,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var SupportTicket $ticket */
    $ticket = SupportTicket::factory()->create([
        'user_id' => $driver->id,
    ]);

    actingAs($admin)
        ->postJson("/api/v1/admin/tickets/{$ticket->id}/comments", [
            'message' => 'We are looking into this issue',
        ])
        ->assertStatus(201);
});

test('non-admin cannot create admin ticket comment', function (): void {
    /** @var User $rider */
    $rider = User::factory()->create([
        'role'              => UserRole::RIDER,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var User $user */
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var SupportTicket $ticket */
    $ticket = SupportTicket::factory()->create([
        'user_id' => $user->id,
    ]);

    actingAs($rider)
        ->postJson("/api/v1/admin/tickets/{$ticket->id}/comments", [
            'message' => 'Unauthorized comment',
        ])
        ->assertStatus(403);
});

test('unauthenticated user cannot create ticket comment', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var SupportTicket $ticket */
    $ticket = SupportTicket::factory()->create([
        'user_id' => $user->id,
    ]);

    postJson("/api/v1/admin/tickets/{$ticket->id}/comments", [
        'message' => 'Unauthorized comment',
    ])
        ->assertStatus(401);
});

test('message is required', function (): void {
    /** @var User $admin */
    $admin = User::factory()->create([
        'role'              => UserRole::ADMIN,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var User $user */
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var SupportTicket $ticket */
    $ticket = SupportTicket::factory()->create([
        'user_id' => $user->id,
    ]);

    actingAs($admin)
        ->postJson("/api/v1/admin/tickets/{$ticket->id}/comments", [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['message']);
});
