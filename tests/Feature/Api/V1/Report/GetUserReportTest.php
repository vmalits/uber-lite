<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Report;

use App\Enums\ProfileStep;
use App\Enums\UserRole;
use App\Models\Report;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

function createUserForReport(UserRole $role = UserRole::RIDER): User
{
    return User::factory()->create([
        'role'              => $role,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
}

test('user can view their own report', function (): void {
    $reporter = createUserForReport();
    $target = createUserForReport();
    $report = Report::factory()->create(['reporter_id' => $reporter->id, 'target_id' => $target->id]);

    actingAs($reporter)
        ->getJson("/api/v1/reports/{$report->id}")
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.id', $report->id)
        ->assertJsonPath('data.reporter_id', $reporter->id)
        ->assertJsonPath('data.target_id', $target->id)
        ->assertJsonStructure([
            'data' => ['id', 'reporter_id', 'target_id', 'ride_id', 'reason', 'description', 'status', 'admin_note', 'resolved_by', 'created_at', 'updated_at'],
        ]);
});

test('user cannot view another users report', function (): void {
    $reporter = createUserForReport();
    $otherUser = createUserForReport();
    $target = createUserForReport();
    $report = Report::factory()->create(['reporter_id' => $reporter->id, 'target_id' => $target->id]);

    actingAs($otherUser)
        ->getJson("/api/v1/reports/{$report->id}")
        ->assertNotFound();
});

test('user gets 404 for nonexistent report', function (): void {
    $user = createUserForReport();

    actingAs($user)
        ->getJson('/api/v1/reports/01JKFAKE000000000000000000')
        ->assertNotFound();
});

test('unauthenticated user cannot view report', function (): void {
    $report = Report::factory()->create();

    getJson("/api/v1/reports/{$report->id}")
        ->assertUnauthorized();
});
