<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\Ride;

interface GetRideQueryInterface
{
    public function execute(string $id): Ride;
}
