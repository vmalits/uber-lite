<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\GetReceiptsRequest;
use App\Models\User;
use App\Queries\Rider\GetReceiptsQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Receipts retrieved successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
final class GetReceiptsController extends Controller
{
    public function __construct(
        private readonly GetReceiptsQueryInterface $query,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        GetReceiptsRequest $request,
    ): JsonResponse {
        return ApiResponse::success(
            $this->query->execute(
                riderId: $user->id,
                perPage: $request->perPage(),
                from: $request->from(),
                to: $request->to(),
            ),
        );
    }
}
