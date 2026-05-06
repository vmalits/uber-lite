<?php

declare(strict_types=1);

namespace App\Queries\Driver;

use App\Data\Driver\DriverReceiptData;
use App\Models\Ride;
use App\Services\Avatar\AvatarUrlResolver;

final readonly class GetDriverReceiptQuery implements GetDriverReceiptQueryInterface
{
    public function __construct(
        private AvatarUrlResolver $avatarResolver,
    ) {}

    public function execute(Ride $ride): DriverReceiptData
    {
        $ride->load(['rider', 'tip', 'rating']);

        return DriverReceiptData::fromModel($ride, $this->avatarResolver);
    }
}
