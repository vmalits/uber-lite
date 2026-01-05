<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Data\Admin\CreateBanData;
use App\Enums\DriverBanType;
use App\Enums\UserStatus;
use App\Exceptions\Driver\DriverAlreadyBannedException;
use App\Models\DriverBan;
use App\Models\User;
use App\Queries\Driver\GetActiveBanQueryInterface;

final readonly class BanDriver
{
    private const int DEFAULT_BAN_HOURS = 24;

    public function __construct(
        private GetActiveBanQueryInterface $getActiveBanQuery,
    ) {}

    public function handle(User $driver, CreateBanData $data): DriverBan
    {
        $ban = $this->getActiveBanQuery->execute($driver);
        if ($ban !== null) {
            throw DriverAlreadyBannedException::forDriver($driver);
        }

        $expiresAt = $data->banType === DriverBanType::TEMPORARY
            ? ($data->expiresAt ?? now()->addHours(self::DEFAULT_BAN_HOURS))
            : null;

        $ban = DriverBan::query()->create([
            'driver_id'   => $driver->id,
            'banned_by'   => $data->bannedBy,
            'ban_source'  => $data->banSource->value,
            'ban_type'    => $data->banType->value,
            'reason'      => $data->reason,
            'external_id' => $data->externalId,
            'expires_at'  => $expiresAt,
        ]);

        $driver->update([
            'status'    => UserStatus::BANNED,
            'banned_at' => now(),
        ]);

        $ban->refresh();

        return $ban;
    }
}
