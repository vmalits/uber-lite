<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Dto\Admin\RideExportFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Admin\ExportRidesRequest;
use App\Models\Ride;
use App\Presenters\Admin\RidesCsvExportPresenterInterface;
use App\Queries\Admin\ExportRidesQueryInterface;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\Header;
use Knuckles\Scribe\Attributes\Response as ScribeResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Group('Admin')]
#[Authenticated]
#[Header('Authorization', 'Bearer <token>')]
#[ScribeResponse(content: 'text/csv', status: 200, description: 'CSV file with rides data.')]
#[ScribeResponse(status: 401, description: 'Unauthorized.')]
#[ScribeResponse(status: 403, description: 'Forbidden - not an admin.')]
final class ExportRidesController extends Controller
{
    public function __construct(
        private readonly ExportRidesQueryInterface $exportRidesQuery,
        private readonly RidesCsvExportPresenterInterface $csvPresenter,
    ) {}

    public function __invoke(ExportRidesRequest $request): StreamedResponse
    {
        $this->authorize('viewAny', Ride::class);

        $filter = RideExportFilter::fromRequest($request);

        $rides = $this->exportRidesQuery->execute($filter);

        return $this->csvPresenter->present($rides);
    }
}
