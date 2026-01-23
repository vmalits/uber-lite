<?php

declare(strict_types=1);

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Events\Support\SupportTicketCommentCreated;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Laravel\Sanctum\Sanctum;

it('creates a support ticket comment for driver', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
    $ticket = SupportTicket::factory()->create(['user_id' => $driver->id]);

    Event::fake([SupportTicketCommentCreated::class]);

    Sanctum::actingAs($driver);

    $payload = [
        'message' => 'Additional details from the driver.',
    ];

    $response = $this->postJson("/api/v1/driver/tickets/{$ticket->id}/comments", $payload);

    $response->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.ticket_id', $ticket->id)
        ->assertJsonPath('data.user_id', $driver->id)
        ->assertJsonPath('data.message', $payload['message']);

    Event::assertDispatched(SupportTicketCommentCreated::class);

    $this->assertDatabaseHas('support_ticket_comments', [
        'ticket_id' => $ticket->id,
        'user_id'   => $driver->id,
        'message'   => $payload['message'],
    ]);
});

it('rejects comment creation for another user ticket', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
    $otherUser = User::factory()->create();
    $ticket = SupportTicket::factory()->create(['user_id' => $otherUser->id]);

    Sanctum::actingAs($driver);

    $response = $this->postJson("/api/v1/driver/tickets/{$ticket->id}/comments", [
        'message' => 'Not allowed.',
    ]);

    $response->assertForbidden();
});

it('returns 404 for non-existent ticket', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);

    Sanctum::actingAs($driver);

    $response = $this->postJson('/api/v1/driver/tickets/non-existent-id/comments', [
        'message' => 'Hello',
    ]);

    $response->assertNotFound();
});

it('validates required message', function (): void {
    $driver = User::factory()->verified()->create([
        'role'         => UserRole::DRIVER,
        'profile_step' => ProfileStep::COMPLETED,
    ]);
    $ticket = SupportTicket::factory()->create(['user_id' => $driver->id]);

    Sanctum::actingAs($driver);

    $response = $this->postJson("/api/v1/driver/tickets/{$ticket->id}/comments", []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['message']);
});

it('rejects unauthenticated request', function (): void {
    $ticket = SupportTicket::factory()->create();

    $response = $this->postJson("/api/v1/driver/tickets/{$ticket->id}/comments", [
        'message' => 'Hello',
    ]);

    $response->assertUnauthorized();
});
