<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\DriverBan;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModelClass of DriverBan
 *
 * @extends Builder<TModelClass>
 */
class DriverBanBuilder extends Builder
{
    /**
     * @return DriverBanBuilder<TModelClass>
     */
    public function active(): self
    {
        return $this->whereNull('unbanned_at');
    }

    /**
     * @return DriverBanBuilder<TModelClass>
     */
    public function forDriver(string $driverId): self
    {
        return $this->where('driver_id', $driverId);
    }
}
