<?php

declare(strict_types=1);

namespace App\Queries\Support;

use App\Enums\SupportTicketStatus;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class GetTicketsQuery implements GetTicketsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, SupportTicket>
     */
    public function execute(User $user, int $perPage): LengthAwarePaginator
    {
        $baseQuery = SupportTicket::query()
            ->where('user_id', $user->id);

        return QueryBuilder::for($baseQuery)
            ->allowedFilters([
                AllowedFilter::callback('status', static function (Builder $query, string $value) {
                    if (SupportTicketStatus::tryFrom($value) !== null) {
                        $query->where('status', $value);
                    }
                }),
                AllowedFilter::partial('subject'),
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
