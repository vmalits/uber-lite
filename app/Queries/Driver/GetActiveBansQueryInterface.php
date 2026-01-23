<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Models\DriverBan;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface GetActiveBansQueryInterface
{
    /**
     * @return Collection<int, DriverBan>
     */
    public function execute(User $driver): Collection;
}
