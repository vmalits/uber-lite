<?php

declare(strict_types=1);

namespace App\Actions\Ride;

use App\Enums\RideSplitStatus;
use App\Enums\RideStatus;
use App\Exceptions\Ride\CannotCancelSplitsException;
use App\Models\Ride;
use App\Models\RideSplit;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class CancelRideSplitsAction
{
    public function __construct(
        private DatabaseManager $databaseManager,
    ) {}

    /**
     * @throws Throwable
     *
     * @return array<int, RideSplit>
     */
    public function handle(Ride $ride): array
    {
        if ($ride->status->isFinal()) {
            throw new CannotCancelSplitsException('Cannot cancel splits for a completed or cancelled ride.');
        }

        if ($ride->status === RideStatus::STARTED) {
            throw new CannotCancelSplitsException('Cannot cancel splits after the ride has started.');
        }

        $pendingSplits = $ride->splits()
            ->where('status', RideSplitStatus::PENDING)
            ->get();

        if ($pendingSplits->isEmpty()) {
            throw new CannotCancelSplitsException('No pending split invitations found for this ride.');
        }

        $cancelledSplits = [];

        $this->databaseManager->transaction(function () use ($pendingSplits, &$cancelledSplits): void {
            foreach ($pendingSplits as $split) {
                $split->update(['status' => RideSplitStatus::CANCELLED]);
                $cancelledSplits[] = $split->refresh();
            }
        });

        return $cancelledSplits;
    }
}
