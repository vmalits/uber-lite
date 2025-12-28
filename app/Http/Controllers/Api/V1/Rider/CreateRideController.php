<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Actions\Rider\CreateRide;
use App\Data\Rider\CreateRideData;
use App\Data\Rider\RideData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\CreateRideRequest;
use App\Models\Ride;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 201, description: 'Ride created successfully.')]
#[Response(status: 422, description: 'Validation errors.')]
#[Response(status: 403, description: 'Profile not completed.')]
final class CreateRideController extends Controller
{
    public function __construct(
        private readonly CreateRide $createRide,
    ) {}

    public function __invoke(CreateRideRequest $request): JsonResponse
    {
        $this->authorize('create', Ride::class);

        /** @var User $user */
        $user = $request->user();

        $ride = $this->createRide->handle(
            $user,
            CreateRideData::from($request->validated()),
        );

        return ApiResponse::created(
            data: RideData::fromModel($ride),
            message: 'Ride created successfully.',
        );
    }
}
