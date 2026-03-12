<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Rider;

use App\Data\Rider\CreditTransactionData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\GetCreditTransactionsRequest;
use App\Models\CreditTransaction;
use App\Models\User;
use App\Queries\Rider\GetCreditTransactionsQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Rider')]
#[Endpoint('Get Credit Transactions', 'Get paginated credit transaction history')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Paginated credit transactions retrieved successfully.')]
final class GetCreditTransactionsController extends Controller
{
    public function __construct(
        private readonly GetCreditTransactionsQueryInterface $query,
    ) {}

    public function __invoke(
        #[CurrentUser] User $user,
        GetCreditTransactionsRequest $request,
    ): JsonResponse {
        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, CreditTransaction> $transactions */
        $transactions = $this->query->execute($user, $perPage);

        $transactions->through(
            fn (CreditTransaction $transaction) => CreditTransactionData::fromModel($transaction),
        );

        return ApiResponse::success($transactions);
    }
}
