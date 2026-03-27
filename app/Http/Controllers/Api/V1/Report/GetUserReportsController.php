<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Report;

use App\Data\Report\ReportData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Report\GetUserReportsQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Reports')]
#[Endpoint('List My Reports', 'List reports created by the current user')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class GetUserReportsController extends Controller
{
    public function __construct(
        private readonly GetUserReportsQueryInterface $query,
    ) {}

    public function __invoke(Request $request, #[CurrentUser] User $user): JsonResponse
    {
        $reports = $this->query->execute(
            userId: $user->id,
            perPage: PaginationHelper::perPage($request),
        );

        return ApiResponse::success(
            data: ReportData::collect($reports),
        );
    }
}
