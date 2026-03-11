<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Enums\PayoutStatus;
use App\Models\DriverPayout;
use Illuminate\Validation\ValidationException;

final readonly class UpdatePayoutStatusAction
{
    public function approve(DriverPayout $payout): DriverPayout
    {
        $this->ensureStatus($payout, PayoutStatus::PENDING);

        $payout->update([
            'status'      => PayoutStatus::APPROVED,
            'approved_at' => now(),
        ]);

        return $payout->refresh()->load('driver');
    }

    public function process(DriverPayout $payout): DriverPayout
    {
        $this->ensureStatus($payout, PayoutStatus::APPROVED);

        $payout->update([
            'status'       => PayoutStatus::PROCESSING,
            'processed_at' => now(),
        ]);

        return $payout->refresh()->load('driver');
    }

    public function complete(DriverPayout $payout): DriverPayout
    {
        $this->ensureStatus($payout, PayoutStatus::PROCESSING);

        $payout->update([
            'status'       => PayoutStatus::COMPLETED,
            'completed_at' => now(),
        ]);

        return $payout->refresh()->load('driver');
    }

    public function fail(DriverPayout $payout, string $reason): DriverPayout
    {
        if (! \in_array($payout->status, [PayoutStatus::PENDING, PayoutStatus::APPROVED, PayoutStatus::PROCESSING], true)) {
            throw ValidationException::withMessages([
                'status' => 'Payout cannot be failed from current status.',
            ]);
        }

        $payout->update([
            'status'         => PayoutStatus::FAILED,
            'failed_at'      => now(),
            'failure_reason' => $reason,
        ]);

        return $payout->refresh()->load('driver');
    }

    private function ensureStatus(DriverPayout $payout, PayoutStatus $expected): void
    {
        if ($payout->status !== $expected) {
            throw ValidationException::withMessages([
                'status' => "Payout must be in {$expected->value} status.",
            ]);
        }
    }
}
