<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Enums\AnnouncementTarget;
use App\Models\Announcement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class GetAnnouncementsQuery implements GetAnnouncementsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Announcement>
     */
    public function execute(int $perPage): LengthAwarePaginator
    {
        return QueryBuilder::for(Announcement::query())
            ->allowedFilters([
                AllowedFilter::callback('target', static function (Builder $query, string $value): void {
                    if (AnnouncementTarget::tryFrom($value) !== null) {
                        $query->where('target', $value);
                    }
                }),
                AllowedFilter::callback('is_active', static function (Builder $query, mixed $value): void {
                    $isActive = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    if ($isActive !== null) {
                        $query->where('is_active', $isActive);
                    }
                }),
            ])
            ->allowedSorts([
                'created_at',
                'updated_at',
                'published_at',
                'title',
            ])
            ->defaultSort('-created_at')
            ->paginate($perPage);
    }
}
