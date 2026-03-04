<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Rider\GetPaymentMethodsQueryInterface;
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
#[Response(status: 200, description: 'Payment methods retrieved successfully.')]
#[Response(status: 401, description: 'Unauthenticated')]
final class GetPaymentMethodsController extends Controller
{
    public function __construct(
        private readonly GetPaymentMethodsQueryInterface $query,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
    ): JsonResponse {
        return ApiResponse::success(
            $this->query->execute($user->id),
        );
    }
}
