<?php

declare(strict_types=1);

namespace App\Queries\Notification;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class GetNotificationsQuery implements GetNotificationsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, DatabaseNotification>
     */
    public function execute(User $user, int $perPage, bool $unreadOnly = false): LengthAwarePaginator
    {
        $baseQuery = DatabaseNotification::query()->where('user_id', $user->id);

        if ($unreadOnly) {
            $baseQuery->whereNull('read_at');
        }

        return QueryBuilder::for($baseQuery)
            ->allowedFilters([
                AllowedFilter::callback('type', static function (\Illuminate\Database\Eloquent\Builder $query, string $value): void {
                    $query->where('type', 'like', "%{$value}%");
                }),
                AllowedFilter::exact('read', 'read_at'),
            ])
            ->allowedSorts([
                'created_at',
                'read_at',
            ])
            ->defaultSort('-created_at')
            ->paginate($perPage);
    }
}
