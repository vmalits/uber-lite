<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Ride\CreateTipForRideAction;
use App\Data\Rider\RiderTipData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\AddTipRequest;
use App\Models\Ride;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group('Rider')]
#[Endpoint('Add Tip', 'Add a tip to a completed ride')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[UrlParam(
    name: 'ride',
    type: 'string',
    description: 'ULID of the ride.',
    required: true,
    example: '01jk9v6v9v6v9v6v9v6v9v6v9v',
)]
#[Response(status: 200, description: 'Tip added successfully.')]
#[Response(status: 403, description: 'Forbidden - You can only add tip to your own completed rides.')]
#[Response(status: 404, description: 'Ride not found.')]
#[Response(status: 422, description: 'Validation failed or tip already exists.')]
final class AddTipController extends Controller
{
    public function __construct(
        private readonly CreateTipForRideAction $createTipForRideAction,
    ) {}

    public function __invoke(AddTipRequest $request, Ride $ride): JsonResponse
    {
        $this->authorize('addTip', $ride);

        $tip = $this->createTipForRideAction->handle(
            ride: $ride,
            amount: $request->integer('amount'),
            comment: $request->string('comment')->toString() ?: null,
        );

        return ApiResponse::success(
            data: RiderTipData::fromModel($tip),
            message: __('messages.ride.tip_added'),
        );
    }
}
