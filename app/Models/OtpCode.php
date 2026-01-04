<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read string $id
 * @property string $phone
 * @property string $code
 * @property Carbon $expires_at
 * @property bool $used
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class OtpCode extends Model
{
    use HasUlids;
    use MassPrunable;

    protected $fillable = [
        'phone',
        'code',
        'expires_at',
        'used',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used'       => 'boolean',
            'code'       => 'hashed',
        ];
    }

    /**
     * @return Builder<OtpCode>
     */
    public function prunable(): Builder
    {
        return self::query()
            ->where(function (Builder $q): void {
                $q->where('used', true)
                    ->orWhere('expires_at', '<', now());
            });
    }
}
