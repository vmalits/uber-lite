<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Data\Admin\CreateAnnouncementData;
use App\Models\Announcement;

final readonly class CreateAnnouncementAction
{
    public function handle(CreateAnnouncementData $data, string $adminId): Announcement
    {
        return Announcement::query()->create([
            'admin_id'     => $adminId,
            'title'        => $data->title,
            'body'         => $data->body,
            'target'       => $data->target,
            'is_active'    => $data->is_active,
            'published_at' => $data->published_at,
            'expires_at'   => $data->expires_at,
        ]);
    }
}
