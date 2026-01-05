<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Data\Admin\UnbanDriverData;
use App\Enums\UserStatus;
use App\Exceptions\Driver\DriverNotBannedException;
use App\Models\DriverBan;
use App\Models\User;
use App\Queries\Driver\GetActiveBanQueryInterface;

final readonly class UnbanDriver
{
    public function __construct(
        private GetActiveBanQueryInterface $getActiveBanQuery,
    ) {}

    public function handle(User $driver, UnbanDriverData $data): DriverBan
    {
        $ban = $this->getActiveBanQuery->execute($driver);
        if ($ban === null) {
            throw DriverNotBannedException::forDriver($driver);
        }

        $ban->update([
            'unbanned_by'  => $data->unbannedBy,
            'unbanned_at'  => now(),
            'unban_reason' => $data->reason,
        ]);

        $driver->update([
            'status'    => UserStatus::ACTIVE,
            'banned_at' => null,
        ]);

        $ban->refresh();

        return $ban;
    }
}
