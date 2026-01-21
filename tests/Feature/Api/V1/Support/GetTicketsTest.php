<?php

declare(strict_types=1);

use App\Enums\SupportTicketStatus;
use App\Models\SupportTicket;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

it('can list support tickets for the authenticated user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    SupportTicket::factory()->count(3)->create(['user_id' => $user->id]);
    SupportTicket::factory()->count(2)->create(['user_id' => $otherUser->id]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/support/tickets');

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonCount(3, 'data.items');
});

it('can filter support tickets by status', function () {
    $user = User::factory()->create();

    SupportTicket::factory()->create([
        'user_id' => $user->id,
        'status'  => SupportTicketStatus::OPEN,
    ]);
    SupportTicket::factory()->create([
        'user_id' => $user->id,
        'status'  => SupportTicketStatus::CLOSED,
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/support/tickets?filter[status]=open');

    $response->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.status', SupportTicketStatus::OPEN->value);
});

it('can filter support tickets by subject', function () {
    $user = User::factory()->create();

    SupportTicket::factory()->create([
        'user_id' => $user->id,
        'subject' => 'Payment issue',
    ]);
    SupportTicket::factory()->create([
        'user_id' => $user->id,
        'subject' => 'Driver complaint',
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/v1/support/tickets?filter[subject]=Payment');

    $response->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.subject', 'Payment issue');
});

it('denies support tickets listing for unauthenticated user', function () {
    $response = $this->getJson('/api/v1/support/tickets');

    $response->assertUnauthorized();
});
