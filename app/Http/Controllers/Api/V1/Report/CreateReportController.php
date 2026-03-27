<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Report;

use App\Actions\Report\CreateReportAction;
use App\Data\Report\CreateReportData;
use App\Data\Report\ReportData;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Report\CreateReportRequest;
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

#[Group('Reports')]
#[Endpoint('Create Report', 'Report a user for a policy violation')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[Response(status: 201, description: 'Report created successfully.')]
#[Response(status: 422, description: 'Validation errors.')]
final class CreateReportController extends Controller
{
    public function __construct(
        private readonly CreateReportAction $createReport,
    ) {}

    public function __invoke(CreateReportRequest $request, #[CurrentUser] User $user): JsonResponse
    {
        $this->authorize('create', Report::class);

        $report = $this->createReport->handle(
            data: CreateReportData::from($request->validated()),
            reporterId: $user->id,
        );

        return ApiResponse::created(
            data: ReportData::fromModel($report),
            message: __('messages.report.created'),
        );
    }
}
