<?php

declare(strict_types=1);

namespace App\Data\Admin;

use App\Data\DateData;
use App\Enums\AnnouncementTarget;
use App\Models\Announcement;
use Spatie\LaravelData\Data;

/**
 * @param string $id
 * @param string $title
 * @param string $body
 * @param AnnouncementTarget $target
 * @param bool $is_active
 * @param DateData|null $published_at
 * @param DateData|null $expires_at
 * @param DateData $created_at
 * @param DateData $updated_at
 */
final class AnnouncementData extends Data
{
    public function __construct(
        public string $id,
        public string $title,
        public string $body,
        public AnnouncementTarget $target,
        public bool $is_active,
        public ?DateData $published_at,
        public ?DateData $expires_at,
        public DateData $created_at,
        public DateData $updated_at,
    ) {}

    public static function fromModel(Announcement $announcement): self
    {
        return new self(
            id: $announcement->id,
            title: $announcement->title,
            body: $announcement->body,
            target: $announcement->target,
            is_active: $announcement->is_active,
            published_at: $announcement->published_at ? DateData::fromCarbon($announcement->published_at) : null,
            expires_at: $announcement->expires_at ? DateData::fromCarbon($announcement->expires_at) : null,
            created_at: DateData::fromCarbon($announcement->created_at),
            updated_at: DateData::fromCarbon($announcement->updated_at),
        );
    }
}
