<?php

declare(strict_types=1);

namespace App\Queries\Announcement;

use App\Enums\AnnouncementTarget;
use App\Enums\UserRole;
use App\Models\Announcement;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class GetActiveAnnouncementsQuery implements GetActiveAnnouncementsQueryInterface
{
    /**
     * @return LengthAwarePaginator<int, Announcement>
     */
    public function execute(UserRole $role, int $perPage): LengthAwarePaginator
    {
        return Announcement::query()
            ->where('is_active', true)
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>=', now()))
            ->where(fn ($q) => $q->where('target', AnnouncementTarget::ALL)->orWhere('target', $this->mapRole($role)))
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    private function mapRole(UserRole $role): AnnouncementTarget
    {
        return match ($role) {
            UserRole::RIDER  => AnnouncementTarget::RIDERS,
            UserRole::DRIVER => AnnouncementTarget::DRIVERS,
            default          => AnnouncementTarget::ALL,
        };
    }
}
