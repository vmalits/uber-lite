<?php

declare(strict_types=1);

namespace App\Queries\DriverDocument;

use App\Models\DriverDocument;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface GetDriverDocumentsQueryInterface
{
    /**
     * @return Collection<int, DriverDocument>
     */
    public function execute(User $user): Collection;
}
