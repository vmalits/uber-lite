<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\DriverPayout;

final readonly class GetPayoutQuery implements GetPayoutQueryInterface
{
    public function execute(string $payoutId): DriverPayout
    {
        return DriverPayout::query()
            ->with('driver')
            ->findOrFail($payoutId);
    }
}
