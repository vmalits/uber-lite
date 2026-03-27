<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Data\Report\ReportData;
use App\Http\Controllers\Controller;
use App\Queries\Admin\GetReportQueryInterface;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;

#[Group('Admin')]
#[Endpoint('Get Report', 'Get a single report details')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
final class GetReportController extends Controller
{
    public function __construct(
        private readonly GetReportQueryInterface $query,
    ) {}

    public function __invoke(string $report): JsonResponse
    {
        $report = $this->query->execute($report);

        $this->authorize('view', $report);

        return ApiResponse::success(
            data: ReportData::fromModel($report),
        );
    }
}
