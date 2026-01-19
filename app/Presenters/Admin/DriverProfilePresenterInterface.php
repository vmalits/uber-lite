<?php

declare(strict_types=1);

namespace App\Presenters\Admin;

use App\Data\Admin\DriverProfileData;
use App\Models\User;

interface DriverProfilePresenterInterface
{
    public function present(User $driver): DriverProfileData;
}
