<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\User;
use Spatie\QueryBuilder\QueryBuilder;

final readonly class GetUserQuery implements GetUserQueryInterface
{
    public function execute(string $id): User
    {
        /** @var User $result */
        $result = QueryBuilder::for(User::where('id', $id))
            ->allowedIncludes(['bans', 'favorites'])
            ->getSubject()
            ->firstOrFail();

        return $result;
    }
}
