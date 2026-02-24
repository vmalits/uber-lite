<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\UpdateRideNoteAction;
use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\UpdateRideNoteRequest;
use App\Models\Ride;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'ride',
    type: 'string',
    description: 'ULID of the ride.',
    required: true,
    example: '01jk9v6v9v6v9v6v9v6v9v6v9v',
)]
#[BodyParam(
    name: 'note',
    type: 'string',
    description: 'Pickup/dropoff instructions for the driver.',
    required: false,
    example: 'Please ring the doorbell twice. Blue house with white gate.',
)]
#[Response(status: 200, description: 'Note updated successfully.')]
#[Response(status: 403, description: 'Forbidden - You can only update notes for your own pending or scheduled rides.')]
#[Response(status: 404, description: 'Ride not found.')]
final class UpdateRideNoteController extends Controller
{
    public function __construct(
        private readonly UpdateRideNoteAction $updateRideNote,
    ) {}

    public function __invoke(UpdateRideNoteRequest $request, Ride $ride): JsonResponse
    {
        $this->authorize('updateNote', $ride);

        $ride = $this->updateRideNote->handle(
            ride: $ride,
            note: $request->getNote(),
        );

        return ApiResponse::success(
            data: RideData::fromModel($ride),
            message: __('messages.ride.note_updated'),
        );
    }
}
