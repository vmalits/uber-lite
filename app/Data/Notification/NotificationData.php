<?php

declare(strict_types=1);

namespace App\Data\Notification;

use Carbon\CarbonInterface;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;

final class NotificationData extends Data
{
    /**
     * @param array<string, mixed>|null $data
     */
    public function __construct(
        public string $id,
        public string $type,
        public ?string $title,
        public ?string $body,
        public ?array $data,
        #[MapName('read_at')]
        public ?CarbonInterface $readAt,
        #[MapName('created_at')]
        public CarbonInterface $createdAt,
    ) {}
}
