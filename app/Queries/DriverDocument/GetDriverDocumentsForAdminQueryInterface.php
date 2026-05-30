<?php

declare(strict_types=1);

namespace App\Queries\DriverDocument;

use App\Models\DriverDocument;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface GetDriverDocumentsForAdminQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, DriverDocument>
     */
    public function execute(User $driver): LengthAwarePaginator;
}
