<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\ProcessedWebhookFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string $id
 * @property string $event_id
 * @property string $event_type
 * @property CarbonInterface $processed_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
final class ProcessedWebhook extends Model
{
    /** @use HasFactory<ProcessedWebhookFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'event_id',
        'event_type',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'processed_at' => 'datetime',
        ];
    }

    public static function alreadyProcessed(string $eventId): bool
    {
        return self::query()
            ->where('event_id', $eventId)
            ->exists();
    }

    public static function markAsProcessed(string $eventId, string $eventType): self
    {
        return self::query()->create([
            'event_id'     => $eventId,
            'event_type'   => $eventType,
            'processed_at' => now(),
        ]);
    }
}
