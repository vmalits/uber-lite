<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\DriverBalanceData;
use App\Enums\PayoutStatus;
use App\Models\DriverPayout;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final readonly class GetDriverBalanceQuery implements GetDriverBalanceQueryInterface
{
    public function execute(User $driver): DriverBalanceData
    {
        $pendingPayouts = (int) DriverPayout::query()
            ->where('driver_id', $driver->id)
            ->whereIn('status', [PayoutStatus::PENDING, PayoutStatus::APPROVED, PayoutStatus::PROCESSING])
            ->sum('amount');

        $totalEarned = (int) DB::table('rides')
            ->where('driver_id', $driver->id)
            ->where('status', 'completed')
            ->sum('price');

        $totalPaidOut = (int) DriverPayout::query()
            ->where('driver_id', $driver->id)
            ->where('status', PayoutStatus::COMPLETED)
            ->sum('amount');

        return new DriverBalanceData(
            balance: $driver->driver_balance,
            pending_payouts: $pendingPayouts,
            total_earned: $totalEarned,
            total_paid_out: $totalPaidOut,
        );
    }
}
