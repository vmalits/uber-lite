<?php

declare(strict_types=1);

namespace App\Actions\Rider;

use App\Models\Ride;

final readonly class UpdateRideNoteAction
{
    public function handle(Ride $ride, ?string $note): Ride
    {
        $ride->update([
            'rider_note' => $note,
        ]);

        return $ride->refresh();
    }
}
