<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Ride;

use App\Actions\Ride\CancelRideSplitsAction;
use App\Data\Ride\Split\RideSplitData;
use App\Exceptions\Ride\CannotCancelSplitsException;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Ride')]
#[Endpoint('Cancel Ride Splits', 'Cancel all pending split invitations for a ride')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Split invitations cancelled successfully.')]
#[Response(status: 401, description: 'Unauthenticated.')]
#[Response(status: 403, description: 'Forbidden - Only the ride owner can cancel splits.')]
#[Response(status: 404, description: 'Ride not found.')]
#[Response(status: 422, description: 'Cannot cancel splits at this time.')]
final class CancelRideSplitsController extends Controller
{
    public function __construct(
        private readonly CancelRideSplitsAction $cancelRideSplits,
    ) {}

    public function __invoke(Ride $ride): JsonResponse
    {
        $this->authorize('split', $ride);

        try {
            $cancelledSplits = $this->cancelRideSplits->handle($ride);
        } catch (CannotCancelSplitsException $e) {
            return ApiResponse::error(
                message: $e->getMessage(),
                status: 422,
            );
        }

        $responseData = array_map(
            fn ($split) => RideSplitData::fromModel($split),
            $cancelledSplits,
        );

        return ApiResponse::success(
            data: [
                'ride_id'          => $ride->id,
                'cancelled_splits' => $responseData,
                'cancelled_count'  => \count($cancelledSplits),
            ],
            message: __('messages.ride.splits_cancelled'),
        );
    }
}
