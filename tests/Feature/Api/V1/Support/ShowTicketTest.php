<?php

declare(strict_types=1);

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('can show support ticket details for the owner', function () {
    $user = User::factory()->create();
    $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($user);

    $response = $this->getJson("/api/v1/support/tickets/{$ticket->id}");

    $response->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.id', $ticket->id)
        ->assertJsonPath('data.subject', $ticket->subject);
});

it('denies support ticket details for another user', function () {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($otherUser);

    $response = $this->getJson("/api/v1/support/tickets/{$ticket->id}");

    $response->assertForbidden();
});

it('allows admin to see any support ticket details', function () {
    $admin = User::factory()->create(['role' => App\Enums\UserRole::ADMIN]);
    $user = User::factory()->create();
    $ticket = SupportTicket::factory()->create(['user_id' => $user->id]);

    Sanctum::actingAs($admin);

    $response = $this->getJson("/api/v1/support/tickets/{$ticket->id}");

    $response->assertOk()
        ->assertJsonPath('data.id', $ticket->id);
});

it('returns 404 for non-existent ticket', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $nonExistentUlid = '01ARZ3NDEKTSV4RRFFQ69G5FAV';
    $response = $this->getJson("/api/v1/support/tickets/{$nonExistentUlid}");

    $response->assertNotFound();
});
