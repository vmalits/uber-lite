<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Actions\Report\ResolveReportAction;
use App\Data\Report\ReportData;
use App\Data\Report\ResolveReportData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\ResolveReportRequest;
use App\Models\Report;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response;

#[Group('Admin')]
#[Endpoint('Resolve Report', 'Update report status with admin note')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 200, description: 'Report resolved successfully.')]
#[Response(status: 422, description: 'Validation errors.')]
final class ResolveReportController extends Controller
{
    public function __construct(
        private readonly ResolveReportAction $resolveReport,
    ) {}

    public function __invoke(ResolveReportRequest $request, Report $report, #[CurrentUser] User $admin): JsonResponse
    {
        $this->authorize('resolve', $report);

        $report = $this->resolveReport->handle(
            report: $report,
            data: ResolveReportData::from($request->validated()),
            adminId: $admin->id,
        );

        return ApiResponse::success(
            data: ReportData::fromModel($report),
            message: __('messages.report.resolved'),
        );
    }
}
