<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Admin;

use App\Enums\UserRole;
use App\Models\SupportTicket;
use App\Models\User;

it('allows admin to list tickets', function () {
    $admin = User::factory()->create(['role' => UserRole::ADMIN]);
    SupportTicket::factory()->count(3)->create();

    $response = $this->actingAs($admin)->getJson('/api/v1/admin/tickets');

    $response->assertOk();
    $response->assertJsonStructure([
        'success',
        'data' => [
            'items' => [
                '*' => [
                    'id',
                    'user_id',
                    'subject',
                    'message',
                    'status',
                    'user' => [
                        'id',
                        'first_name',
                        'last_name',
                        'phone',
                        'email',
                        'avatar_urls',
                        'role',
                        'locale',
                        'profile_step',
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                    'created_at',
                    'updated_at',
                ],
            ],
            'pagination' => [
                'total',
                'per_page',
                'current_page',
                'last_page',
            ],
        ],
    ]);
    $response->assertJsonCount(3, 'data.items');
});

it('forbids non-admin from listing tickets', function () {
    $user = User::factory()->create(['role' => UserRole::RIDER]);
    $response = $this->actingAs($user)->getJson('/api/v1/admin/tickets');
    $response->assertForbidden();
});
