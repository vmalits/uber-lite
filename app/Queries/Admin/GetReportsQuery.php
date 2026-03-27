<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Enums\ReportStatus;
use App\Models\Report;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class GetReportsQuery implements GetReportsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Report>
     */
    public function execute(int $perPage): LengthAwarePaginator
    {
        return QueryBuilder::for(Report::query()->with(['reporter', 'target']))
            ->allowedFilters([
                AllowedFilter::callback('status', static function (Builder $query, string $value): void {
                    if (ReportStatus::tryFrom($value) !== null) {
                        $query->where('status', $value);
                    }
                }),
                AllowedFilter::exact('reporter_id'),
                AllowedFilter::exact('target_id'),
                AllowedFilter::exact('reason'),
            ])
            ->allowedSorts([
                'created_at',
                'updated_at',
                'status',
            ])
            ->defaultSort('-created_at')
            ->paginate($perPage);
    }
}
