<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\SupportTicket;
use App\Models\User;

it('returns ticket details for admin', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    $ticket = SupportTicket::factory()->create();

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson("/api/v1/admin/tickets/{$ticket->id}");

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.id', $ticket->id)
        ->assertJsonPath('data.subject', $ticket->subject);
});

it('returns 404 for non-existent ticket', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);

    $response = $this->actingAs($admin, 'sanctum')
        ->getJson('/api/v1/admin/tickets/non-existent-id');

    $response->assertNotFound();
});

it('rejects unauthorized request', function () {
    $ticket = SupportTicket::factory()->create();

    $response = $this->getJson("/api/v1/admin/tickets/{$ticket->id}");

    $response->assertUnauthorized();
});

it('rejects non-admin user', function () {
    $user = User::factory()->create(['role' => UserRole::RIDER]);
    $ticket = SupportTicket::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->getJson("/api/v1/admin/tickets/{$ticket->id}");

    $response->assertForbidden();
});
