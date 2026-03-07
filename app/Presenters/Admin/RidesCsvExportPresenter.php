<?php

declare(strict_types=1);

namespace App\Presenters\Admin;

use App\Dto\Admin\RideExportMapper;
use App\Dto\Admin\RideExportRow;
use Illuminate\Support\LazyCollection;
use Symfony\Component\HttpFoundation\StreamedResponse;

final readonly class RidesCsvExportPresenter implements RidesCsvExportPresenterInterface
{
    private const array CSV_HEADERS = [
        'ID',
        'Status',
        'Rider ID',
        'Rider Name',
        'Rider Phone',
        'Driver ID',
        'Driver Name',
        'Driver Phone',
        'Origin Address',
        'Destination Address',
        'Price',
        'Discount Amount',
        'Estimated Distance (km)',
        'Estimated Duration (min)',
        'Created At',
        'Started At',
        'Completed At',
        'Cancelled At',
        'Cancelled By',
        'Cancellation Reason',
    ];

    public function __construct(
        private RideExportMapper $mapper,
    ) {}

    public function present(LazyCollection $rides): StreamedResponse
    {
        $rows = $this->mapper->map($rides);

        return response()->stream(
            fn () => $this->generateCsv($rows),
            200,
            $this->headers(),
        );
    }

    /**
     * @return array<string, string>
     */
    private function headers(): array
    {
        return [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="rides_export_'.date('Y-m-d_His').'.csv"',
            'Cache-Control'       => 'no-cache, no-store, must-revalidate',
            'Pragma'              => 'no-cache',
        ];
    }

    /**
     * @param LazyCollection<int, RideExportRow> $rows
     */
    private function generateCsv(LazyCollection $rows): void
    {
        $file = fopen('php://output', 'wb');

        if ($file === false) {
            return;
        }

        fprintf($file, \chr(0xEF).\chr(0xBB).\chr(0xBF));
        fputcsv($file, self::CSV_HEADERS);

        foreach ($rows as $row) {
            fputcsv($file, $this->toArray($row));
        }

        fclose($file);
    }

    /**
     * @return array<int, string|null|float>
     */
    private function toArray(RideExportRow $row): array
    {
        return [
            $row->id,
            $row->status,
            $row->riderId,
            $row->riderName,
            $row->riderPhone,
            $row->driverId,
            $row->driverName,
            $row->driverPhone,
            $row->originAddress,
            $row->destinationAddress,
            $row->price,
            $row->discountAmount,
            $row->estimatedDistanceKm,
            $row->estimatedDurationMin,
            $row->createdAt,
            $row->startedAt,
            $row->completedAt,
            $row->cancelledAt,
            $row->cancelledBy,
            $row->cancellationReason,
        ];
    }
}
