<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\Announcement;

final readonly class DeleteAnnouncementAction
{
    public function handle(Announcement $announcement): bool
    {
        return (bool) $announcement->delete();
    }
}
