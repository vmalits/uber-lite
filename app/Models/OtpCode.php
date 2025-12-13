<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $phone
 * @property string $code
 * @property Carbon $expires_at
 * @property bool $used
 */
class OtpCode extends Model
{
    use HasUlids;

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
}
