<?php

declare(strict_types=1);

namespace App\Models;

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
        ];
    }

    /**
     * @return Builder<OtpCode>
     */
    public function prunable(): Builder
    {
        return self::query()
            ->where(function ($q) {
                $q->where('used', true)
                    ->orWhere('expires_at', '<', now());
            });
    }
}
