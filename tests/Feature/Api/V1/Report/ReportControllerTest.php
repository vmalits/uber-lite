<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1\Report;

use App\Enums\ProfileStep;
use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use App\Enums\UserRole;
use App\Models\Report;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\postJson;

function createUser(UserRole $role = UserRole::RIDER): User
{
    return User::factory()->create([
        'role'              => $role,
        'profile_step'      => ProfileStep::COMPLETED,
        'phone_verified_at' => now(),
        'email_verified_at' => now(),
    ]);
}

function validPayload(User $target): array
{
    return [
        'target_id'   => $target->id,
        'reason'      => ReportReason::UNSAFE_DRIVING->value,
        'description' => 'The driver was speeding and running red lights.',
    ];
}

test('user can create a report', function (): void {
    $reporter = createUser();
    $target = createUser();

    actingAs($reporter)
        ->postJson('/api/v1/reports', validPayload($target))
        ->assertCreated()
        ->assertJsonPath('success', true)
        ->assertJsonPath('data.reason', 'unsafe_driving')
        ->assertJsonPath('data.status', 'pending')
        ->assertJsonPath('data.target_id', $target->id);
});

test('user can report with all reasons', function (): void {
    $reporter = createUser();
    $target = createUser();

    foreach (ReportReason::cases() as $reason) {
        $payload = validPayload($target);
        $payload['reason'] = $reason->value;

        actingAs($reporter)
            ->postJson('/api/v1/reports', $payload)
            ->assertCreated()
            ->assertJsonPath('data.reason', $reason->value);
    }
});

test('user can create report without description', function (): void {
    $reporter = createUser();
    $target = createUser();
    $payload = validPayload($target);
    unset($payload['description']);

    actingAs($reporter)
        ->postJson('/api/v1/reports', $payload)
        ->assertCreated();
});

test('target_id is required', function (): void {
    $reporter = createUser();

    actingAs($reporter)
        ->postJson('/api/v1/reports', [
            'reason' => ReportReason::HARASSMENT->value,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['target_id']);
});

test('target_id must exist', function (): void {
    $reporter = createUser();

    actingAs($reporter)
        ->postJson('/api/v1/reports', [
            'target_id' => '01HXXXXXXXXXXXXXXXXXXXXXXXX',
            'reason'    => ReportReason::HARASSMENT->value,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['target_id']);
});

test('reason is required', function (): void {
    $reporter = createUser();
    $target = createUser();

    actingAs($reporter)
        ->postJson('/api/v1/reports', [
            'target_id' => $target->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['reason']);
});

test('reason must be valid enum', function (): void {
    $reporter = createUser();
    $target = createUser();

    actingAs($reporter)
        ->postJson('/api/v1/reports', [
            'target_id' => $target->id,
            'reason'    => 'not_a_reason',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['reason']);
});

test('unauthenticated user cannot create report', function (): void {
    $target = createUser();

    postJson('/api/v1/reports', validPayload($target))
        ->assertUnauthorized();
});

test('user can list their own reports', function (): void {
    $reporter = createUser();
    $target = createUser();
    Report::factory()->count(3)->create(['reporter_id' => $reporter->id, 'target_id' => $target->id]);
    Report::factory()->count(2)->create(['reporter_id' => $target->id, 'target_id' => $reporter->id]);

    actingAs($reporter)
        ->getJson('/api/v1/reports')
        ->assertOk()
        ->assertJsonCount(3, 'data.items');
});

test('user reports list is empty initially', function (): void {
    $user = createUser();

    actingAs($user)
        ->getJson('/api/v1/reports')
        ->assertOk()
        ->assertJsonCount(0, 'data.items');
});

test('response has correct json structure', function (): void {
    $reporter = createUser();
    $target = createUser();

    actingAs($reporter)
        ->postJson('/api/v1/reports', validPayload($target))
        ->assertCreated()
        ->assertJsonStructure([
            'success',
            'data' => ['id', 'reporter_id', 'target_id', 'ride_id', 'reason', 'description', 'status', 'admin_note', 'resolved_by', 'created_at', 'updated_at'],
        ]);
});

test('admin can list all reports', function (): void {
    $admin = createUser(UserRole::ADMIN);
    Report::factory()->count(3)->create();

    actingAs($admin)
        ->getJson('/api/v1/admin/reports')
        ->assertOk()
        ->assertJsonCount(3, 'data.items');
});

test('non-admin cannot list all reports', function (): void {
    $rider = createUser();

    actingAs($rider)
        ->getJson('/api/v1/admin/reports')
        ->assertForbidden();
});

test('admin can view single report', function (): void {
    $admin = createUser(UserRole::ADMIN);
    $report = Report::factory()->create();

    actingAs($admin)
        ->getJson("/api/v1/admin/reports/{$report->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $report->id);
});

test('admin can resolve a report', function (): void {
    $admin = createUser(UserRole::ADMIN);
    $report = Report::factory()->create(['status' => ReportStatus::PENDING]);

    actingAs($admin)
        ->putJson("/api/v1/admin/reports/{$report->id}/resolve", [
            'status'     => ReportStatus::RESOLVED->value,
            'admin_note' => 'Driver warned and penalized.',
        ])
        ->assertOk()
        ->assertJsonPath('data.status', 'resolved')
        ->assertJsonPath('data.admin_note', 'Driver warned and penalized.')
        ->assertJsonPath('data.resolved_by', $admin->id);
});

test('admin can dismiss a report', function (): void {
    $admin = createUser(UserRole::ADMIN);
    $report = Report::factory()->create();

    actingAs($admin)
        ->putJson("/api/v1/admin/reports/{$report->id}/resolve", [
            'status'     => ReportStatus::DISMISSED->value,
            'admin_note' => 'No evidence found.',
        ])
        ->assertOk()
        ->assertJsonPath('data.status', 'dismissed');
});

test('non-admin cannot resolve a report', function (): void {
    $rider = createUser();
    $report = Report::factory()->create();

    actingAs($rider)
        ->putJson("/api/v1/admin/reports/{$report->id}/resolve", [
            'status' => ReportStatus::RESOLVED->value,
        ])
        ->assertForbidden();
});

test('admin can filter reports by status', function (): void {
    $admin = createUser(UserRole::ADMIN);
    Report::factory()->create(['status' => ReportStatus::PENDING]);
    Report::factory()->resolved()->create();

    actingAs($admin)
        ->getJson('/api/v1/admin/reports?filter[status]=pending')
        ->assertOk()
        ->assertJsonCount(1, 'data.items')
        ->assertJsonPath('data.items.0.status', 'pending');
});

test('driver can report rider', function (): void {
    $driver = createUser(UserRole::DRIVER);
    $rider = createUser(UserRole::RIDER);

    actingAs($driver)
        ->postJson('/api/v1/reports', validPayload($rider))
        ->assertCreated();
});

test('rider can report driver', function (): void {
    $rider = createUser(UserRole::RIDER);
    $driver = createUser(UserRole::DRIVER);

    actingAs($rider)
        ->postJson('/api/v1/reports', validPayload($driver))
        ->assertCreated();
});
