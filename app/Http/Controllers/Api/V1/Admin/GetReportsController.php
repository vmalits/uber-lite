<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Report\ReportData;
use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Queries\Admin\GetReportsQueryInterface;
use App\Support\ApiResponse;
use App\Support\PaginationHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Admin')]
#[Endpoint('Get Reports', 'Get list of all reports')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class GetReportsController extends Controller
{
    public function __construct(
        private readonly GetReportsQueryInterface $query,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Report::class);

        $reports = $this->query->execute(PaginationHelper::perPage($request));

        return ApiResponse::success(
            data: ReportData::collect($reports),
        );
    }
}
