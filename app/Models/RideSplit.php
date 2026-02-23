<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Database\Factories\RideSplitFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $id
 * @property string $ride_id
 * @property string $participant_name
 * @property string|null $participant_email
 * @property string|null $participant_phone
 * @property float|null $share
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property-read Ride $ride
 */
#[UseFactory(RideSplitFactory::class)]
final class RideSplit extends Model
{
    /** @use HasFactory<RideSplitFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'ride_id',
        'participant_name',
        'participant_email',
        'participant_phone',
        'share',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'share' => 'decimal:2',
        ];
    }

    /**
     * @return BelongsTo<Ride, $this>
     */
    public function ride(): BelongsTo
    {
        return $this->belongsTo(Ride::class);
    }
}
