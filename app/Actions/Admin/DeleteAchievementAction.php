<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Achievement;

final readonly class DeleteAchievementAction
{
    public function handle(Achievement $achievement): bool
    {
        return (bool) $achievement->delete();
    }
}
