<?php

declare(strict_types=1);

namespace App\Presenters\User;

use App\Data\User\ProfileResponse;
use App\Models\User;

interface UserProfilePresenterInterface
{
    public function present(User $user): ProfileResponse;
}
