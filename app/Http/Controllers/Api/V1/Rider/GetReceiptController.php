<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Models\User;
use App\Queries\Rider\GetReceiptQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Get Receipt', 'Get receipt details for a completed ride')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Receipt retrieved successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
#[Response(status: 403, description: 'Forbidden')]
#[Response(status: 404, description: 'Receipt not found')]
final class GetReceiptController extends Controller
{
    public function __construct(
        private readonly GetReceiptQueryInterface $query,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        Ride $ride,
    ): JsonResponse {
        $this->authorize('viewReceipt', $ride);

        return ApiResponse::success(
            $this->query->execute($ride),
        );
    }
}
