<?php

declare(strict_types=1);

namespace App\Queries\Rider;

use App\Data\Rider\ReceiptData;
use App\Models\Ride;
use App\Services\Avatar\AvatarUrlResolver;

final readonly class GetReceiptQuery implements GetReceiptQueryInterface
{
    public function __construct(
        private AvatarUrlResolver $avatarResolver,
    ) {}

    public function execute(Ride $ride): ReceiptData
    {
        $ride->load(['driver', 'tip', 'rating', 'promoCode']);

        return ReceiptData::fromModel($ride, $this->avatarResolver);
    }
}
