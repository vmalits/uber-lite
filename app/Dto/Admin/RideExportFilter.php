<?php

declare(strict_types=1);

namespace App\Dto\Admin;

use Illuminate\Http\Request;

final readonly class RideExportFilter
{
    public function __construct(
        public ?string $dateFrom = null,
        public ?string $dateTo = null,
        public ?string $status = null,
        public ?string $riderId = null,
        public ?string $driverId = null,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            dateFrom: self::castToString($request->input('date_from')),
            dateTo: self::castToString($request->input('date_to')),
            status: self::castToString($request->input('status')),
            riderId: self::castToString($request->input('filter.rider_id')),
            driverId: self::castToString($request->input('filter.driver_id')),
        );
    }

    public function hasDateRange(): bool
    {
        return $this->dateFrom !== null || $this->dateTo !== null;
    }

    public function hasFilters(): bool
    {
        return $this->status !== null
            || $this->riderId !== null
            || $this->driverId !== null
            || $this->hasDateRange();
    }

    private static function castToString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return \is_string($value) ? $value : null;
    }
}
