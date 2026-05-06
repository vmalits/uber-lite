<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Enums\ActorType;
use App\Enums\RideStatus;
use App\Exceptions\Ride\InvalidRideTransition;
use App\Models\Ride;
use App\Support\RideStateMachine;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;
use Throwable;

final readonly class CancelRideAction
{
    public function __construct(
        private RideStateMachine $stateMachine,
        private DatabaseManager $databaseManager,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(Ride $ride, string $reason, ?string $adminId = null): Ride
    {
        return $this->databaseManager->transaction(
            callback: function () use ($ride, $reason, $adminId): Ride {
                if ($ride->status->isFinal()) {
                    throw ValidationException::withMessages([
                        'ride' => ['Ride cannot be cancelled in its current status.'],
                    ]);
                }

                try {
                    $this->stateMachine->transition(
                        ride: $ride,
                        to: RideStatus::CANCELLED,
                        actorType: ActorType::ADMIN,
                        actorId: $adminId,
                        meta: ['reason' => $reason],
                    );
                } catch (InvalidRideTransition) {
                    throw ValidationException::withMessages([
                        'ride' => ['Invalid ride status transition.'],
                    ]);
                }

                return $ride->refresh();
            },
            attempts: 3,
        );
    }
}
