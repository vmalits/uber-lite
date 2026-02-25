<?php

declare(strict_types=1);

namespace App\Actions\Admin;

use App\Data\Admin\CreatePromoCodeData;
use App\Models\PromoCode;

final readonly class UpdatePromoCodeAction
{
    public function handle(PromoCode $promoCode, CreatePromoCodeData $data): PromoCode
    {
        $promoCode->update([
            'code'                 => strtoupper($data->code),
            'title'                => $data->title,
            'description'          => $data->description,
            'discount_type'        => $data->discount_type,
            'discount_value'       => $data->discount_value,
            'max_discount_amount'  => $data->max_discount_amount,
            'min_order_amount'     => $data->min_order_amount,
            'usage_limit'          => $data->usage_limit,
            'usage_limit_per_user' => $data->usage_limit_per_user,
            'starts_at'            => $data->starts_at,
            'expires_at'           => $data->expires_at,
            'is_active'            => $data->is_active,
        ]);

        return $promoCode->refresh();
    }
}
