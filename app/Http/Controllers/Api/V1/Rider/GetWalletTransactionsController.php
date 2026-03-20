<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\GetCreditTransactionsRequest;
use App\Models\User;
use App\Queries\Rider\GetWalletTransactionsQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Get Wallet Transactions', 'Get wallet-related credit transactions')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Transactions retrieved successfully.')]
final class GetWalletTransactionsController extends Controller
{
    public function __construct(
        private readonly GetWalletTransactionsQueryInterface $query,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        GetCreditTransactionsRequest $request,
    ): JsonResponse {
        $perPage = PaginationHelper::perPage($request);

        $transactions = $this->query->execute($user, $perPage);

        return ApiResponse::success($transactions);
    }
}
