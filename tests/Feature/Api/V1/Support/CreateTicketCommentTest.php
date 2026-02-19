<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Support;

use App\Models\SupportTicket;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can create a comment for a support ticket', function () {
    $user = User::factory()->create();
    $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/support/tickets/{$ticket->id}/comments", [
        'message' => 'This is a test comment.',
    ]);

    $response->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.message', 'This is a test comment.')
        ->assertJsonPath('data.user_id', $user->id)
        ->assertJsonPath('data.ticket_id', $ticket->id);
});

it('can create multiple comments for a support ticket', function () {
    $user = User::factory()->create();
    $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $this->postJson("/api/v1/support/tickets/{$ticket->id}/comments", [
        'message' => 'First comment.',
    ]);

    $response = $this->postJson("/api/v1/support/tickets/{$ticket->id}/comments", [
        'message' => 'Second comment.',
    ]);

    $response->assertCreated();

    $this->assertDatabaseHas('support_ticket_comments', [
        'ticket_id' => $ticket->id,
        'message'   => 'First comment.',
    ]);

    $this->assertDatabaseHas('support_ticket_comments', [
        'ticket_id' => $ticket->id,
        'message'   => 'Second comment.',
    ]);
});

it('validates message is required', function () {
    $user = User::factory()->create();
    $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/support/tickets/{$ticket->id}/comments", []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['message']);
});

it('validates message is not empty', function () {
    $user = User::factory()->create();
    $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/support/tickets/{$ticket->id}/comments", [
        'message' => '',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['message']);
});

it('validates message maximum length', function () {
    $user = User::factory()->create();
    $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/support/tickets/{$ticket->id}/comments", [
        'message' => str_repeat('a', 5001),
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['message']);
});

it('denies creating comment for another user ticket', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $ticket = SupportTicket::factory()->create(['user_id' => $otherUser->id]);

    Sanctum::actingAs($user);

    $response = $this->postJson("/api/v1/support/tickets/{$ticket->id}/comments", [
        'message' => 'Unauthorized comment.',
    ]);

    $response->assertForbidden();
});

it('denies creating comment for unauthenticated user', function () {
    $ticket = SupportTicket::factory()->create();

    $response = $this->postJson("/api/v1/support/tickets/{$ticket->id}/comments", [
        'message' => 'Unauthorized comment.',
    ]);

    $response->assertUnauthorized();
});
