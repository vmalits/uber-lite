<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Http\Request;

final class PaginationHelper
{
    public static function perPage(Request $request, ?int $default = 15, int $min = 2, int $max = 50): int
    {
        $effectiveDefault = $default ?? $min;
        $perPage = $request->integer('per_page', $effectiveDefault);

        return max($min, min($perPage, $max));
    }
}
