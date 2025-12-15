<?php

declare(strict_types=1);

namespace App\Queries\Auth;

use App\Models\User;

interface FindUserByPhoneQueryInterface
{
    public function execute(string $phone): ?User;
}
