<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Ride;

use App\Actions\Ride\RespondToSplitAction;
use App\Data\Ride\Split\RideSplitData;
use App\Enums\RideSplitStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Ride\RespondToSplitRequest;
use App\Models\Ride;
use App\Models\RideSplit;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Ride')]
#[Endpoint('Respond to Split', 'Accept or decline a split ride invitation')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Split invitation responded to successfully.')]
#[Response(status: 401, description: 'Unauthenticated.')]
#[Response(status: 404, description: 'Ride or split not found.')]
#[Response(status: 422, description: 'Validation failed or split already responded to.')]
final class RespondToSplitController extends Controller
{
    public function __construct(
        private readonly RespondToSplitAction $respondToSplit,
    ) {}

    public function __invoke(RespondToSplitRequest $request, Ride $ride): JsonResponse
    {
        /** @var array{split_id: string, status: string} $validated */
        $validated = $request->validated();

        $split = RideSplit::query()
            ->where('id', $validated['split_id'])
            ->where('ride_id', $ride->id)
            ->firstOrFail();

        if ($split->status !== RideSplitStatus::PENDING) {
            return ApiResponse::error(
                message: 'This split invitation has already been responded to.',
                status: 422,
            );
        }

        $split = $this->respondToSplit->handle(
            $ride,
            $split,
            RideSplitStatus::from($validated['status']),
        );

        return ApiResponse::success(
            data: RideSplitData::fromModel($split),
            message: __('messages.ride.split_responded'),
        );
    }
}
