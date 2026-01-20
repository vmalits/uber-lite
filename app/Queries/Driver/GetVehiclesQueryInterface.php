<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;

interface GetVehiclesQueryInterface
{
    /**
     * @return Collection<int, Vehicle>
     */
    public function execute(User $user): Collection;
}
