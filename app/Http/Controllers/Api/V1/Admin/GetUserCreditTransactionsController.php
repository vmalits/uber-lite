<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Rider\CreditTransactionData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Rider\GetCreditTransactionsRequest;
use App\Models\CreditTransaction;
use App\Models\User;
use App\Queries\Admin\GetUserCreditTransactionsQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Admin')]
#[Endpoint('Get User Credit History', 'Get paginated credit transaction history for a specific user')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class GetUserCreditTransactionsController extends Controller
{
    public function __construct(
        private readonly GetUserCreditTransactionsQueryInterface $query,
    ) {}

    public function __invoke(GetCreditTransactionsRequest $request, User $user): JsonResponse
    {
        $this->authorize('adjustCredits', $user);

        $perPage = PaginationHelper::perPage($request);

        /** @var LengthAwarePaginator<int, CreditTransaction> $transactions */
        $transactions = $this->query->execute($user->id, $perPage);

        $transactions->through(
            fn (CreditTransaction $transaction) => CreditTransactionData::fromModel($transaction),
        );

        return ApiResponse::success($transactions);
    }
}
