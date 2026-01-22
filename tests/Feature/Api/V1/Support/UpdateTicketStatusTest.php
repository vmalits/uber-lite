<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Support;

use App\Enums\SupportTicketStatus;
use App\Models\SupportTicket;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can update support ticket status', function () {
    $user = User::factory()->create();
    $ticket = SupportTicket::factory()->create([
        'user_id' => $user->id,
        'status'  => SupportTicketStatus::OPEN,
    ]);

    Sanctum::actingAs($user);

    $response = $this->putJson("/api/v1/support/tickets/{$ticket->id}/status", [
        'status' => SupportTicketStatus::CLOSED->value,
    ]);

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.status', SupportTicketStatus::CLOSED->value);

    expect($ticket->fresh()->status)->toBe(SupportTicketStatus::CLOSED);
});

it('allows admin to update support ticket status', function () {
    $admin = User::factory()->admin()->create();
    $ticket = SupportTicket::factory()->create([
        'status' => SupportTicketStatus::OPEN,
    ]);

    Sanctum::actingAs($admin);

    $response = $this->putJson("/api/v1/support/tickets/{$ticket->id}/status", [
        'status' => SupportTicketStatus::PENDING->value,
    ]);

    $response->assertOk()
        ->assertJsonPath('data.status', SupportTicketStatus::PENDING->value);
});

it('denies access to update status of another user ticket', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $ticket = SupportTicket::factory()->create(['user_id' => $otherUser->id]);

    Sanctum::actingAs($user);

    $response = $this->putJson("/api/v1/support/tickets/{$ticket->id}/status", [
        'status' => SupportTicketStatus::CLOSED->value,
    ]);

    $response->assertForbidden();
});

it('validates status during update', function () {
    $user = User::factory()->create();
    $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->putJson("/api/v1/support/tickets/{$ticket->id}/status", [
        'status' => 'invalid-status',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['status']);
});

it('requires status for update', function () {
    $user = User::factory()->create();
    $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->putJson("/api/v1/support/tickets/{$ticket->id}/status", []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['status']);
});
