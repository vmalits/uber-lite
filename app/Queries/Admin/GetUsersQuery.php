<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class GetUsersQuery
{
    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function execute(int $perPage): LengthAwarePaginator
    {
        $baseQuery = User::query()
            ->with('bans');

        return QueryBuilder::for($baseQuery)
            ->allowedFilters([
                AllowedFilter::callback('role', static function (Builder $query, string $value) {
                    if (UserRole::tryFrom($value) !== null) {
                        $query->where('role', $value);
                    }
                }),
                AllowedFilter::callback('status', static function (Builder $query, string $value) {
                    if (UserStatus::tryFrom($value) !== null) {
                        $query->where('status', $value);
                    }
                }),
                AllowedFilter::callback('banned', static function (Builder $query, mixed $value) {
                    $isBanned = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                    if ($isBanned === true) {
                        $query->whereNotNull('banned_at');
                    }
                    if ($isBanned === false) {
                        $query->whereNull('banned_at');
                    }
                }),
                AllowedFilter::partial('phone'),
                AllowedFilter::partial('email'),
                AllowedFilter::partial('first_name'),
                AllowedFilter::partial('last_name'),
            ])
            ->allowedSorts([
                'created_at',
                'updated_at',
                'last_login_at',
                'phone',
                'email',
            ])
            ->defaultSort('-created_at')
            ->paginate($perPage);
    }
}
