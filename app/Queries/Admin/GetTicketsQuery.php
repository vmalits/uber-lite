<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Enums\SupportTicketStatus;
use App\Models\SupportTicket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class GetTicketsQuery implements GetTicketsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, SupportTicket>
     */
    public function execute(int $perPage): LengthAwarePaginator
    {
        $baseQuery = SupportTicket::query()
            ->with(['user']);

        return QueryBuilder::for($baseQuery)
            ->allowedFilters([
                AllowedFilter::callback('status', static function (Builder $query, string $value) {
                    if (SupportTicketStatus::tryFrom($value) !== null) {
                        $query->where('status', $value);
                    }
                }),
                AllowedFilter::exact('user_id'),
                AllowedFilter::partial('subject'),
            ])
            ->allowedSorts([
                'created_at',
                'updated_at',
                'subject',
                'status',
            ])
            ->defaultSort('-created_at')
            ->paginate($perPage);
    }
}
