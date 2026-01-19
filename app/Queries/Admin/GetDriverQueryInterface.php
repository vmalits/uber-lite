<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\User;

interface GetDriverQueryInterface
{
    public function execute(string $id): User;
}
