<?php

declare(strict_types=1);

use App\Enums\SupportTicketStatus;
use App\Models\SupportTicket;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can create a support ticket', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $payload = [
        'subject' => 'Issue with payment',
        'message' => 'I was charged twice for my last ride.',
    ];

    $response = $this->postJson('/api/v1/support/tickets', $payload);

    $response->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('message', __('messages.support.ticket_created'))
        ->assertJsonPath('data.subject', 'Issue with payment')
        ->assertJsonPath('data.message', 'I was charged twice for my last ride.')
        ->assertJsonPath('data.status', SupportTicketStatus::OPEN->value);

    $this->assertDatabaseHas('support_tickets', [
        'user_id' => $user->id,
        'subject' => 'Issue with payment',
        'message' => 'I was charged twice for my last ride.',
        'status'  => SupportTicketStatus::OPEN->value,
    ]);
});

it('can create a support ticket using factory', function () {
    $ticket = SupportTicket::factory()->create();

    expect($ticket)->toBeInstanceOf(SupportTicket::class);
    $this->assertDatabaseHas('support_tickets', [
        'id' => $ticket->id,
    ]);
});

it('validates support ticket creation', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $response = $this->postJson('/api/v1/support/tickets', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['subject', 'message']);
});
