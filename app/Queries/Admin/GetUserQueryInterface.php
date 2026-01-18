<?php

declare(strict_types=1);

namespace App\Queries\Admin;

use App\Models\User;

interface GetUserQueryInterface
{
    public function execute(string $id): User;
}
