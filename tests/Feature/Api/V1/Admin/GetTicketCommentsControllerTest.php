<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\SupportTicket;
use App\Models\SupportTicketComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns ticket comments for admin', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $ticket = SupportTicket::factory()->create();

    SupportTicketComment::factory()->count(2)->create([
        'ticket_id' => $ticket->id,
    ]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson("/api/v1/admin/tickets/{$ticket->id}/comments");

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonCount(2, 'data');
});

it('returns 404 for non-existent ticket', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/tickets/non-existent-id/comments');

    $response->assertNotFound();
});

it('rejects unauthorized request', function () {
    $ticket = SupportTicket::factory()->create();

    $response = $this->getJson("/api/v1/admin/tickets/{$ticket->id}/comments");

    $response->assertUnauthorized();
});

it('rejects non-admin user', function () {
    $user = User::factory()->create(['role' => UserRole::RIDER]);
    $ticket = SupportTicket::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->getJson("/api/v1/admin/tickets/{$ticket->id}/comments");

    $response->assertForbidden();
});
