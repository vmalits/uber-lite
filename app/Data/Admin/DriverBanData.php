<?php

declare(strict_types=1);

namespace App\Data\Admin;

use App\Data\DateData;
use App\Models\DriverBan;
use Spatie\LaravelData\Data;

class DriverBanData extends Data
{
    public function __construct(
        public string $id,
        public string $driver_id,
        public ?string $banned_by,
        public string $ban_source,
        public string $ban_type,
        public string $reason,
        public ?string $external_id,
        public ?DateData $expires_at,
        public ?DateData $unbanned_at,
        public ?string $unbanned_by,
        public ?string $unban_reason,
        public DateData $created_at,
        public DateData $updated_at,
    ) {}

    public static function fromModel(DriverBan $ban): self
    {
        return new self(
            id: $ban->id,
            driver_id: $ban->driver_id,
            banned_by: $ban->banned_by,
            ban_source: $ban->ban_source,
            ban_type: $ban->ban_type,
            reason: $ban->reason,
            external_id: $ban->external_id,
            expires_at: $ban->expires_at ? DateData::fromCarbon($ban->expires_at) : null,
            unbanned_at: $ban->unbanned_at ? DateData::fromCarbon($ban->unbanned_at) : null,
            unbanned_by: $ban->unbanned_by,
            unban_reason: $ban->unban_reason,
            created_at: DateData::fromCarbon($ban->created_at),
            updated_at: DateData::fromCarbon($ban->updated_at),
        );
    }
}
