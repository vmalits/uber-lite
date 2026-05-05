<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Report;

use App\Data\Report\ReportData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Queries\Report\GetUserReportQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Reports')]
#[Endpoint('Get Report', 'Get details of a specific report created by the current user')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class GetUserReportController extends Controller
{
    public function __construct(
        private readonly GetUserReportQueryInterface $query,
    ) {}

    public function __invoke(Request $request, string $report): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $report = $this->query->execute($report, $user->id);

        return ApiResponse::success(
            data: ReportData::fromModel($report),
        );
    }
}
