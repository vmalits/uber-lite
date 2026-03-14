<?php

declare(strict_types=1);

namespace App\Data\Ride;

use App\Data\DateData;
use App\Data\User\UserData;
use App\Models\RideMessage;
use App\Services\Avatar\AvatarUrlResolver;
use Spatie\LaravelData\Data;

final class RideMessageData extends Data
{
    public function __construct(
        public string $id,
        public string $ride_id,
        public string $sender_id,
        public string $message,
        public UserData $sender,
        public DateData $created_at,
        public ?DateData $read_at,
    ) {}

    public static function fromModel(RideMessage $message): self
    {
        /** @var AvatarUrlResolver $avatarResolver */
        $avatarResolver = app(AvatarUrlResolver::class);

        return new self(
            id: $message->id,
            ride_id: $message->ride_id,
            sender_id: $message->sender_id,
            message: $message->message,
            sender: UserData::fromModel($message->sender, $avatarResolver),
            created_at: DateData::fromCarbon($message->created_at),
            read_at: $message->read_at ? DateData::fromCarbon($message->read_at) : null,
        );
    }
}
