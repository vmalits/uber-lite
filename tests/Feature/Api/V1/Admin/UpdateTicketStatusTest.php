<?php

declare(strict_types=1);

use App\Enums\SupportTicketStatus;
use App\Enums\UserRole;
use App\Models\SupportTicket;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\putJson;

test('admin can update ticket status', function (): void {
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
        'status'  => SupportTicketStatus::OPEN,
    ]);

    actingAs($admin)
        ->putJson("/api/v1/admin/tickets/{$ticket->id}/status", [
            'status' => 'closed',
        ])
        ->assertStatus(200)
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.status', 'closed');

    $ticket->refresh();

    expect($ticket->status)->toBe(SupportTicketStatus::CLOSED);
});

test('admin can set ticket to pending', function (): void {
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
        'status'  => SupportTicketStatus::OPEN,
    ]);

    actingAs($admin)
        ->putJson("/api/v1/admin/tickets/{$ticket->id}/status", [
            'status' => 'pending',
        ])
        ->assertStatus(200)
        ->assertJsonPath('data.status', 'pending');
});

test('non-admin cannot update ticket status', function (): void {
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
        'status'  => SupportTicketStatus::OPEN,
    ]);

    actingAs($rider)
        ->putJson("/api/v1/admin/tickets/{$ticket->id}/status", [
            'status' => 'closed',
        ])
        ->assertStatus(403);
});

test('unauthenticated user cannot update ticket status', function (): void {
    /** @var User $user */
    $user = User::factory()->create([
        'role'              => UserRole::RIDER,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);

    /** @var SupportTicket $ticket */
    $ticket = SupportTicket::factory()->create([
        'user_id' => $user->id,
        'status'  => SupportTicketStatus::OPEN,
    ]);

    putJson("/api/v1/admin/tickets/{$ticket->id}/status", [
        'status' => 'closed',
    ])
        ->assertStatus(401);
});

test('status must be valid', function (): void {
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
        'status'  => SupportTicketStatus::OPEN,
    ]);

    actingAs($admin)
        ->putJson("/api/v1/admin/tickets/{$ticket->id}/status", [
            'status' => 'invalid_status',
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['status']);
});

test('status is required', function (): void {
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
        'status'  => SupportTicketStatus::OPEN,
    ]);

    actingAs($admin)
        ->putJson("/api/v1/admin/tickets/{$ticket->id}/status", [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['status']);
});
