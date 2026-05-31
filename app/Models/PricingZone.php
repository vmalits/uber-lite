<?php

declare(strict_types=1);

namespace App\Models;

use App\Policies\PricingZonePolicy;
use Carbon\CarbonInterface;
use Database\Factories\PricingZoneFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read string $id
 * @property string $name
 * @property string $slug
 * @property bool $is_enabled
 * @property float $surge_multiplier
 * @property string|null $reason
 * @property float $center_lat
 * @property float $center_lng
 * @property int $radius_meters
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
#[UsePolicy(PricingZonePolicy::class)]
class PricingZone extends Model
{
    /** @use HasFactory<PricingZoneFactory> */
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'name',
        'slug',
        'is_enabled',
        'surge_multiplier',
        'reason',
        'center_lat',
        'center_lng',
        'radius_meters',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled'       => 'boolean',
            'surge_multiplier' => 'decimal:2',
            'center_lat'       => 'decimal:7',
            'center_lng'       => 'decimal:7',
            'radius_meters'    => 'integer',
        ];
    }
}
