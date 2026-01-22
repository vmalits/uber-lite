<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Support;

use App\Models\SupportTicket;
use App\Models\SupportTicketComment;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can list comments for a support ticket', function () {
    $user = User::factory()->create();
    $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);

    SupportTicketComment::factory()->count(3)->create([
        'ticket_id' => $ticket->id,
        'user_id'   => $user->id,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson("/api/v1/support/tickets/{$ticket->id}/comments");

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonCount(3, 'data');
});

it('orders comments by creation date (oldest first)', function () {
    $user = User::factory()->create();
    $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);

    $comment1 = SupportTicketComment::factory()->create([
        'ticket_id'  => $ticket->id,
        'created_at' => now()->subMinutes(10),
    ]);
    $comment2 = SupportTicketComment::factory()->create([
        'ticket_id'  => $ticket->id,
        'created_at' => now()->subMinutes(5),
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson("/api/v1/support/tickets/{$ticket->id}/comments");

    $response->assertOk()
        ->assertJsonPath('data.0.id', $comment1->id)
        ->assertJsonPath('data.1.id', $comment2->id);
});

it('denies access to comments of another user ticket', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $ticket = SupportTicket::factory()->create(['user_id' => $otherUser->id]);

    Sanctum::actingAs($user);

    $response = $this->getJson("/api/v1/support/tickets/{$ticket->id}/comments");

    $response->assertForbidden();
});

it('denies access for unauthenticated user', function () {
    $ticket = SupportTicket::factory()->create();

    $response = $this->getJson("/api/v1/support/tickets/{$ticket->id}/comments");

    $response->assertUnauthorized();
});
