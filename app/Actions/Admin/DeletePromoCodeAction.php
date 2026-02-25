<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Models\PromoCode;

final readonly class DeletePromoCodeAction
{
    public function handle(PromoCode $promoCode): bool
    {
        return (bool) $promoCode->delete();
    }
}
