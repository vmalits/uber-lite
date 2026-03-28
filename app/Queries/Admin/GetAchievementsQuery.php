<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Enums\AchievementCategory;
use App\Models\Achievement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class GetAchievementsQuery implements GetAchievementsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Achievement>
     */
    public function execute(int $perPage): LengthAwarePaginator
    {
        $baseQuery = Achievement::query();

        return QueryBuilder::for($baseQuery)
            ->allowedFilters([
                AllowedFilter::callback('category', static function (Builder $query, string $value): void {
                    if (AchievementCategory::tryFrom($value) !== null) {
                        $query->where('category', $value);
                    }
                }),
                AllowedFilter::callback('is_active', static function (Builder $query, mixed $value): void {
                    $isActive = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    if ($isActive !== null) {
                        $query->where('is_active', $isActive);
                    }
                }),
                AllowedFilter::callback('key', static function (Builder $query, string $value): void {
                    $query->where('key', 'like', '%'.strtolower($value).'%');
                }),
                AllowedFilter::callback('name', static function (Builder $query, string $value): void {
                    $query->where('name', 'like', '%'.$value.'%');
                }),
            ])
            ->allowedSorts([
                'created_at',
                'updated_at',
                'name',
                'points_reward',
                'target_value',
            ])
            ->defaultSort('-created_at')
            ->paginate($perPage);
    }
}
