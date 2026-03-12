<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

final class GetReferralsQuery implements GetReferralsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, User>
     */
    public function execute(User $user, int $perPage): LengthAwarePaginator
    {
        $baseQuery = User::query()
            ->where('referred_by', $user->id);

        /** @var QueryBuilder<User> $query */
        $query = QueryBuilder::for($baseQuery)
            ->defaultSort('-referred_at');

        return $query->paginate($perPage);
    }
}
