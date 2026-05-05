<?php

declare(strict_types=1);

namespace App\Actions\Ride;

use App\Enums\RideSplitStatus;
use App\Models\Ride;
use App\Models\RideSplit;
use Illuminate\Database\DatabaseManager;
use Throwable;

final readonly class RespondToSplitAction
{
    public function __construct(
        private DatabaseManager $databaseManager,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(Ride $ride, RideSplit $split, RideSplitStatus $status): RideSplit
    {
        return $this->databaseManager->transaction(
            callback: function () use ($split, $status): RideSplit {
                $split->update([
                    'status'       => $status,
                    'responded_at' => now(),
                ]);

                return $split->refresh();
            },
            attempts: 3,
        );
    }
}
